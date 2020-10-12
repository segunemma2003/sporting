<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    protected function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_login' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:users',
//            'phone' => 'required|numeric|min:10|unique:users',
            'user_pass' => 'required|string|min:4|confirmed',
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create([
            'user_login' => $data['user_login'],
            'user_email' => $data['user_email'],
            'user_pass' => Hash::make($data['user_pass']),
        ]);

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $success['user_info'] =  $user;
        $success['access_token'] =  $tokenResult->accessToken;

        return $this->sendResponse($success, 'Registration Successful. Kindly check your email.');
    }
}
