<?php
namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    // protected $defaultIncludes = [
    //     'author'
    // ];




	public function transform(User $user)
	{
 
        // foreach($user->fillable as $key){
         

        //     $result[] = [
        //         'key'               =>  $key,
        //         'rules'             =>  $user->rules[$key],
        //         'value'             =>  isset($values[$key])?$values[$key]:null,
        //         'type'              =>  $user->fields[$key]['type'],
        //         'templateOptions'   =>  [
        //             'required'          =>      (strpos('required',$user->rules[$key]))?true:false,
        //             'label'             =>      $user->fields[$key]['label']
        //         ]
        //     ];
        // }


        // return $result;
	}


	/**
     * Include Author
     *
     * @param Book $book
     * @return \League\Fractal\Resource\Item
     */
    // public function includeEthnicOrigins(User $user)
    // {
    //      $ethnicOrigins = $user->ethnicOrigins;

    //      return $this->item($ethnicOrigins, new EthnicOrigin);
    // }

}