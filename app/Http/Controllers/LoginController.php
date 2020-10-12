<?php

namespace App\Http\Controllers\API;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends BaseController
{
    public function login(Request $request)
    {
        $user =  $this->validateUser($request);

        $tokenResult = $user->createToken('Personal Access Token');
//        $user->api_token = $tokenResult->accessToken;
//        $user->save();

        $token = $tokenResult->token;

//        Auth::guard()->login($user);

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return $this->sendResponse([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 'Login Successful');
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->sendResponse([], "Successfully logged out");
    }

    private function validateUser(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return $this->sendError('Unauthorized', [], 401);

        return $request->user();
    }

    public function changePassword (Request $request){
        $user = $this->validateUser($request);

        User::where('id', $user->id)->update(['password' => Hash::make($request->password)]);

        return $this->sendResponse([], "Successfully Changed Password");

    }
}
