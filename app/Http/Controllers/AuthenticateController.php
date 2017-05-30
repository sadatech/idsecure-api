<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class AuthenticateController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email');
        $user = User::where($credentials)->first();
        $provider = $request->provider;

        try {
            // attempt to verify the credentials and create a token for the user
            if( count($user) == 0 ) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $token = JWTAuth::fromUser($user);
            $user->update(['oauth' => $request->oauth]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact(['token', 'provider']));
    }
}
