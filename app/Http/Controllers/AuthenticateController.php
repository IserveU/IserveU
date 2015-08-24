<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\User;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Events\UserLoginFailed;
use App\Events\UserForgotPassword;
use Carbon\Carbon;

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

        try {
         
            // attempt to verify the credentials and create a token for the user
           if (! $token = JWTAuth::attempt($credentials)) {

                event(new UserLoginFailed($credentials));

                return response()->json(['error' => 'invalid_credentials'], 401);
            }

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = Auth::user();

        if($user->locked_until && $user->locked_until->gt(Carbon::now())){
            abort(401,'Account is locked until '.$user->locked_until);
        }

        $user->remember_token = null;
        $user->save();

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
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
  
        $user->remember_token = null;
        $user->save();

        // all good so return the token
        return response()->json(compact('token','user'));

    }

    public function resetPassword(Request $request){
        $credentials = $request->only('email', 'password');
        event(new UserLoginFailed($credentials));

        return response()->json(array('message'=>'password reset sent'));
    }
}
