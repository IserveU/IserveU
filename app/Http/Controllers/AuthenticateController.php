<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\User;
use Auth;
use App\Events\User\UserLoginFailed;
use App\Events\SendPasswordReset;
use Carbon\Carbon;
use Hash;
use App\Events\User\UserLoginSucceeded;


class AuthenticateController extends ApiController
{

    /**
     * Return a JWT for the user.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {

        if (!$user = User::where(['email' => $request->email])->first()){
            return  response(["error"=>"Invalid credentials","message"=>"This user does not exist"],401); 
        }

        if($user && $user->locked_until && $user->locked_until->gt(Carbon::now())){
            abort(401,'Account is locked until '.$user->locked_until);
        }

        if(!Hash::check($request->password, $user->password)){
            event(new UserLoginFailed($user));
            return  response(["error"=>"Invalid credentials","message"=>"Either your username or password are incorrect"],401); 
        }

        event(new UserLoginSucceeded($user));

        return response(["api_token"=>$user->api_token,'user'=>$user],200);

    }

    public function noPassword($remember_token){

        if(empty($remember_token)){
            abort(403,'No password reset code provided');
        }

        $user = User::where('remember_token',$remember_token)->first();

        if(!$user){
            abort(404,'Reset token invalid or expired');
        }

        return response(["api_token"=>$user->api_token,'user'=>$user],200);

        event(new UserLoginSucceeded($user));

    }

    public function resetPassword(Request $request){
        $credentials = $request->only('email', 'password');       
        event(new SendPasswordReset($credentials));
        
        return response()->json(array('message'=>'password reset sent'));
    }
}
