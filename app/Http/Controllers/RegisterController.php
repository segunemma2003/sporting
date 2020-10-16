<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
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
            'user_email' => 'required|string|email|max:255|unique:wpw7_users',
            'user_pass' => 'required|string|min:4|confirmed',
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create([
            'user_login' => $data['user_login'],
            'user_email' => $data['user_email'],
            'user_pass' => Hash::make($data['user_pass']),
            'user_registered' => Carbon::now()
        ]);

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $success['user_info'] =  User::where('user_email', $request->input('user_email'))->first();
        $success['access_token'] =  $tokenResult->accessToken;

        return $this->sendResponse($success, 'Registration Successful');
    }
}
