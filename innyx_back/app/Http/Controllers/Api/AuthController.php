<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'username' => 'required|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'username' => $request->username,
        'password' => bcrypt($request->password), // ou Hash::make()
    ]);

    return response()->json([
        'user' => $user,
    ]);
}

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return response()->json([
            'user' => auth()->user(),
            'token' => $token,
        ]);
    }
}
