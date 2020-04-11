<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = \App\User::create($validated);
        return response()->json(['token' => $user->createToken('PAT')->accessToken]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        if (Auth::attempt($validated)) {
            return response()->json(['token' => auth()->user()->createToken('PAT')->accessToken]);
        } else {
            return response()->json(['errors' => ['password' => ['Password is incorrect!']]], 422);
        }
    }
}
