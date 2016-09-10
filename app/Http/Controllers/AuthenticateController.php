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

            return  response(["error"=>"Invalid credentials","message"=>'Account is locked until '.$user->locked_until],401); 
        }

        if(!Hash::check($request->password, $user->password)){
            event(new UserLoginFailed($user));
            return  response(["error"=>"Invalid credentials","message"=>"Either your username or password are incorrect"],403); 
        }

        event(new UserLoginSucceeded($user));

        $user = $user->fresh();
        $user->skipVisibility();

        return $user;
    }

    public function noPassword($remember_token){

        if(empty($remember_token)){
            return  response(["error"=>"No password reset code provided","message"=>'Account is locked until '.$user->locked_until],403); 
        }

        $user = User::where('remember_token',$remember_token)->first();

        if(!$user){
            return  response(["error"=>"Reset Token Invalid","message"=>"Reset token invalid or not found"],404);
        }       

        event(new UserLoginSucceeded($user));
        return response(["api_token"=>$user->api_token,'user'=>$user],200);
    }

    public function resetPassword(Request $request){
        $credentials = $request->only('email', 'password');       
        event(new SendPasswordReset($credentials));
        
        return response()->json(array('message'=>'password reset sent'));
    }
}
