<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|max:55',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed'
            ]);
    
            $validateData['password'] = Hash::make($request->password);
    
            $user = User::create($validateData);

            $user->makeHidden('id'); // Hide the 'id' attribute
    
            $accessToken = $user->createToken('authToken')->accessToken;
    
            return response(['user' => $user, 'access_token' => $accessToken], 201);
        } catch (ValidationException $e) {
            return response(['message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response(['message' => 'Internal Server Error'], 500);
        }
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => "User not found!"], 400);
        }

        $user = Auth::user();
        $user->makeHidden('id'); // Hide the 'id' attribute

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'user' => $user, 
            'access_token' => $accessToken
        ]);

        // $accessToken = auth()->user()->createToken('authToken')->accessToken;

        // return response([
        //     'user' => auth()->user(), 'access_token' => $accessToken
        // ]);
    }
}
