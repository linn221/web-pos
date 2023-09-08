<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class ApiAuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            abort(401, 'user name or password wrong');
        }

        if (Auth::user()->role == 'ban') {
            abort(403, 'You have been banned');
        }

        return Auth::user()->createToken($request->device ?? 'unknown')->plainTextToken;
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "logout successful"
        ]);
    }

    public function logoutAll()
    {
        foreach (Auth::user()->tokens as $token) {
            $token->delete();
        }
        return response()->json([
            "message" => "logout all devices successful"
        ]);
    }

    public function tokens()
    {
        return Auth::user()->tokens;
    }
}
