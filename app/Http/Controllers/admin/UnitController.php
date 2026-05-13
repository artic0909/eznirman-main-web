<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $units = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'status' => 'required|in:active,inactive',
        ]);

        Unit::create($request->all());

        return redirect()->back()->with('success', 'Unit created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update($request->all());

        return redirect()->back()->with('success', 'Unit updated successfully.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->back()->with('success', 'Unit deleted successfully.');
    }
}
