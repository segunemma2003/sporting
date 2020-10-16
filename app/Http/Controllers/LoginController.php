<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        if(!$user)
            return $this->sendError('Unauthorized', [], 401);

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return $this->sendResponse([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'user_info' => User::where('user_email', $request->input('user_email'))->first(),
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
            'user_email' => 'required|string',
            'user_pass' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

//        $credentials = request(['user_email', 'user_pass']);

        if (!Auth::attempt(['user_email' => $request->input('user_email'), 'password' => $request->input('user_pass')]))
            return false;

        return User::find(User::where('user_email', $request->input('user_email'))->value('ID'));
    }

    public function changePassword (Request $request){
        $user = $this->validateUser($request);

        User::where('id', $user->id)->update(['user_pass' => Hash::make($request->password)]);

        return $this->sendResponse([], "Successfully Changed Password");

    }
}
