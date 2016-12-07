<?php

namespace App\Http\Controllers;

use App\Notifications\Authentication\AccountLocked;
use App\Notifications\Authentication\PasswordReset;
use App\User;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Setting;

class AuthenticateController extends ApiController
{
    /**
     * Return a JWT for the user.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        if (!$user = User::where(['email' => $request->email])->first()) {
            return  response(['error' => 'Invalid credentials', 'message' => 'This user does not exist'], 401);
        }

        if ($user && $user->locked_until && $user->locked_until->gt(Carbon::now())) {
            return  response(['error' => 'Invalid credentials', 'message' => 'Account is locked until '.$user->locked_until], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            $user->login_attempts = $user->login_attempts + 1;
            $user->save();

            if ($user->login_attempts > Setting::get('authentication.login_attempts_lock')) {
                $user->locked_until = Carbon::now()->addHours(3);

                $user->notify(new AccountLocked($user));
            }

            return  response(['error' => 'Invalid credentials', 'message' => 'Either your username or password are incorrect'], 403);
        }

        //TODO: Setup the password resets table, or just use API token $user->login_token
        $user->login_attempts = 0;
        $user->locked_until = null;

        $user->save();

        Auth::setUser($user);

        return $user;
    }

    public function noPassword($remember_token)
    {
        if (empty($remember_token)) {
            return  response(['error' => 'No password reset code provided', 'message' => 'Account is locked until '.$user->locked_until], 403);
        }

        $user = User::where('remember_token', $remember_token)->first();

        if (!$user) {
            return  response(['error' => 'Reset Token Invalid', 'message' => 'Reset token invalid or not found'], 404);
        }

        //TODO: Setup some "User login" function tied to a job on the User model (code duplication above)
        $user->login_attempts = 0;
        $user->locked_until = null;

        $user = $user->fresh();

        Auth::setUser($user);

        return response(['api_token' => $user->api_token, 'user' => $user], 200);
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $user->notify(new PasswordReset($user));

        return response()->json(['message' => 'password reset sent']);
    }
}
