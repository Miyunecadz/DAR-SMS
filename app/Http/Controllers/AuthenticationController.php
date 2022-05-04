<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            // return response()->json(['errors' => $validator->errors()], 400);

            return response()->json($validator->getMessageBag(), 400);
        }

        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
            $request->session()->regenerate();
            return response()->json([
                'token' => $request->user()->createToken($request->username),
                'user' => $request->user()
            ]);
        }

        return response()->json([
            'username' => 'The provided credentials are incorrect'
        ], 422);
    }

}
