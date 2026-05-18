<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $account = Auth::guard('account')->user();
        return view('account.settings.index', compact('account'));
    }

    public function update(Request $request)
    {
        $account = Auth::guard('account')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:accounts,email,' . $account->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $account->name = $request->name;
        $account->email = $request->email;

        if ($request->password) {
            $account->password = Hash::make($request->password);
        }

        $account->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
