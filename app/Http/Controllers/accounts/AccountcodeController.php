<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accountcode;
use Illuminate\Http\Request;

class AccountcodeController extends Controller
{
    public function index(Request $request)
    {
        $query = Accountcode::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $accountCodes = $query->latest()->paginate(10)->withQueryString();
        return view('account.accountcode.index', compact('accountCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:accountcodes,name',
        ]);

        Accountcode::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Account Code created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:accountcodes,name,' . $id,
        ]);

        $accountCode = Accountcode::findOrFail($id);
        $accountCode->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Account Code updated successfully.');
    }

    public function destroy($id)
    {
        $accountCode = Accountcode::findOrFail($id);
        $accountCode->delete();

        return redirect()->back()->with('success', 'Account Code deleted successfully.');
    }
}
