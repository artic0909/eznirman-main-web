<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->check() ? Auth::guard('web')->user() : Auth::guard('admin')->user();
        return view('admin.settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $isWeb = Auth::guard('web')->check();
        $user = $isWeb ? Auth::guard('web')->user() : Auth::guard('admin')->user();
        $table = $isWeb ? 'users' : 'admins';

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . $table . ',email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
