<?php

namespace App\Http\Controllers\accounts\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountAuthController extends Controller
{
    public function loginView()
    {
        return view('account.auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::guard('account')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()
                    ->route('account.dashboard')
                    ->with('success', 'Welcome back to Accounts Portal!');
            }

            return back()
                ->with('error', 'Invalid email or password. Please try again.')
                ->withInput($request->only('email'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->with('error', $e->errors()[array_key_first($e->errors())][0])
                ->withInput();
        } catch (\Throwable $e) {
            Log::error('Account Login Error: ' . $e->getMessage());
            return back()
                ->with('error', 'Something went wrong. Please try again later.')
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('account')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('account.login')->with('success', 'You have been logged out successfully.');
    }
}
