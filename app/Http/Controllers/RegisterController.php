<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    protected function register(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
//            'phone' => 'required|numeric|min:10|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
//            'phone' => $data['phone'],
//            'username' => $data['username'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        $tokenResult = $user->createToken('Personal Access Token');
//        $user->api_token = $tokenResult->accessToken;
//        $user->save();

        $token = $tokenResult->token;

//        Auth::guard()->login($user);

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
//        $user = Auth::user();
//        var_dump($user);

        $success['user_info'] =  $user;
        $success['access_token'] =  $tokenResult->accessToken;

        return $this->sendResponse($success, 'Registration Successful. Kindly check your email.');
    }
}
