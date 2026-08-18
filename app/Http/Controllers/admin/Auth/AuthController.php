<?php

namespace App\Http\Controllers\admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::guard('admin')->attempt($credentials)) {

                $admin = Auth::guard('admin')->user();

                $request->session()->regenerate();

                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Welcome back, ' . $admin->name . '!');
            } elseif (Auth::guard('web')->attempt($credentials)) {
                $user = Auth::guard('web')->user();
                $isCoordinator = \App\Models\Coordinator::where('user_id', $user->id)->exists();
                
                if ($isCoordinator) {
                    $request->session()->regenerate();
                    return redirect()
                        ->route('coordinator.dashboard')
                        ->with('success', 'Welcome back, Coordinator ' . $user->name . '!');
                } else {
                    Auth::guard('web')->logout();
                }
            }

            return back()
                ->with('error', 'Invalid email or password. Please try again.')
                ->withInput($request->only('email'));

        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()
                ->with('error', $e->errors()[array_key_first($e->errors())][0])
                ->withInput();

        } catch (\Throwable $e) {

            Log::error('Admin Login Error: ' . $e->getMessage());

            return back()
                ->with('error', 'Something went wrong. Please try again later.')
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
