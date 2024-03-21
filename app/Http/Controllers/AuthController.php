<?php

namespace App\Http\Controllers;

use App\Models\User; // Add this line to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
class AuthController extends Controller{ 
    public function register(Request $request){ 
        return $user = User::create([
            'name' => $request->input(key: 'name'),
            'email' => $request->input(key: 'email'), 
            'password' => Hash::make($request->input(key: 'password'))
        ]);
    }

    public function login(Request $request)
    { 
        if (!Auth::attempt($request->only('email', 'password'))){
            return response([
                'message' => "Invalid credentials"
            ], status:Response::HTTP_UNAUTHORIZED); 
        }

        $user = Auth::user(); 
        
        //this is not error bruh
        $token = $user->createToken("token")->plainTextToken;
        $cookie = cookie("jwt", $token, minutes:60*24); // 1day 
        
        return response([
            'message' => $token
        ])->withCookie($cookie); 
    }

    public function user() 
    { 

        return Auth::user(); 
    }
}