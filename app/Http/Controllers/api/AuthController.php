<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'code';

        $user = User::where($loginType, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Load the related site, wallet, and the wallet's transactions ordered by date
        $user->load([
            'site',
            'wallet',
            'wallet.transactions' => function ($query) {
                $query->orderBy('date', 'desc')->take(10);
            }
        ]);

        return response()->json([
            'user' => $user,
            'wallet' => $user->wallet,
            'site' => $user->site,
            'transactions' => $user->wallet ? $user->wallet->transactions : [],
        ]);
    }
}
