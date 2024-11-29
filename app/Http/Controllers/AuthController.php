<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // dd($request->all());
        $userdata = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',

        ]);
        if (!Auth::attempt($userdata)) {
            return response()->json(['error' => 'invalid credentials']);
        }
        $userdata = Auth::user();
        $token = $userdata->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function register(Request $request)
    {
        $userdata = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required',

        ]);

        $userdata = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'phone'=> $request->input('phone'),
            'email'=> $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
    }
    public function logout(Request $request)
    {
        // Revoke the token of the currently authenticated user
        $request->user()->currentAccessToken()->delete();

        // Optionally, return a success response
        return response()->json(['message' => 'Successfully logged out']);
    }


    }

