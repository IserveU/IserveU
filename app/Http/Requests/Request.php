<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Request extends FormRequest
{
    /**
   * Reverses the order of the parent trait to first validate the input then do auth.
   * This is because a lot of filter auth was checking inputs and they were being submitted invalid.
   *
   * @return void
   */
  public function validateThenAuth($instance = null)
  {
      $this->prepareForValidation();

      if (!$instance) {
          $instance = $this->getValidatorInstance();
      }

      if (!$instance->passes()) {
          $this->failedValidation($instance);
      } elseif (!$this->passesAuthorization()) {
          $this->failedAuthorization();
      }
  }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

    public function response(array $errors)
    {
        //Might need to just apply errors to the specific methods
         return new JsonResponse($errors, 400);
    }

    // OPTIONAL OVERRIDE
    public function forbiddenResponse()
    {
        if (!Auth::check()) {
            return \Redirect::guest('/login');
        }
        //Probably someone trying to get into something they haven't got permission
        return new Response('Forbidden', 403);
    }

    //Courtesy of @Kiniamaro
    public function validate()
    {
        if (config('app.bouncer') == 'true') {
            $validator = $this->getValidatorInstance();

            // Add universal rules
            $validator->setRules(array_merge(
                ['api_token' => 'string', '_method' => 'string'],
                $validator->getRules())
            );

            $data = $this->all();
            $invalidData = $this->getInvalidData($data, $validator->getRules());

            if ($invalidData) {
                $this->setInvalidDataRules($invalidData, $validator);
            }

            $this->validateThenAuth($validator);
            //
            // // stolen from the parent validation method
            // if (!parent::passesAuthorization()) {
            //     parent::failedAuthorization();
            // } elseif (!$validator->passes()) {
            //     $rules = $validator->getRules();
            //     $validator->getMessageBag()->merge($this->getFormatedRules($rules));
            //     parent::failedValidation($validator);
            // }
        } else {
            $this->validateThenAuth();
        }
    }

    // formats the rules to be sent to the viewbag
    private function getFormatedRules(array $rules)
    {
        $formatedRulesArray = [];
        foreach ($rules as $key => $ruleArray) {
            $formatedRulesArray[$key] = $key.' : '.implode(',', $ruleArray);
        }

        return $formatedRulesArray;
    }

    // checks if an element of the request is not expected by the api
    private function getInvalidData(array $requestData, array $rules)
    {
        $invalidData = [];

        // Makes sure that dot notation isn't done on non-array values. Probably won't work for everything
        foreach ($requestData as $key => $value) {
            if (!is_numeric($value) && is_array($value) && isAssoc($value)) {
                $requestData = array_dot($requestData);
            }
        }

        foreach ($requestData as $key => $value) {
            if (!array_key_exists($key, $rules)) {
                $invalidData[] = $key;
            }
        }

        return $invalidData;
    }

    private function setInvalidDataRules(array $invalidData, Validator $validator)
    {
        $rules = $validator->getRules();
        $newRules = [];
        foreach ($invalidData as $key => $value) {
            $newRules[$value] = 'notRequired';
        }
        $validator->setRules(array_merge($rules, $newRules));
    }
}
