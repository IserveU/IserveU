<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\User;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Events\User\UserLoginFailed;
use App\Events\SendPasswordReset;
use Carbon\Carbon;

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
        $credentials = $request->only('email', 'password');
        
        $checkUser = User::where('email',$credentials['email'])->first();

        if($checkUser && $checkUser->locked_until && $checkUser->locked_until->gt(Carbon::now())){
            abort(401,'Account is locked until '.$checkUser->locked_until);
        }

        try {         
            // attempt to verify the credentials and create a token for the user
           if (! $token = JWTAuth::attempt($credentials)) {
                event(new UserLoginFailed($credentials));
               
                abort(401,"Invalid credentials");
            }

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            abort(500,'Unable to create token');
            
        }

        $user = Auth::user();


        event(new UserLoginSucceeded($user));


        // all good so return the token
        return response()->json(compact('token','user'));
    }

    public function noPassword($remember_token){

        try{
            if(empty($remember_token)){
                abort(403,'No password reset code provided');
            }

            $user = User::where('remember_token',$remember_token)->first();

            if(!$user){
                abort(404,'Reset token invalid or expired');
            }

            $token = JWTAuth::fromUser($user);
            Auth::loginUsingId($user->id);
            
        } catch (JWTException $e) {
            abort(500,'could not create token');
        }
  
        event(new UserLoginSucceeded($user));


        // all good so return the token
        return response()->json(compact('token','user'));

    }

    public function resetPassword(Request $request){
        $credentials = $request->only('email', 'password');       
        event(new SendPasswordReset($credentials));
        
        return response()->json(array('message'=>'password reset sent'));
    }
}
