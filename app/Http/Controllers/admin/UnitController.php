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

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'units_export.xlsx',
                function ($unit) {
                    return [
                        'Name' => $unit->name,
                        'Status' => ucfirst($unit->status),
                        'Created At' => $unit->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            );
        }

        $units = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.purchase.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['creator_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['creator_type'] = 'admin';
        }
        Unit::create($data);

        return redirect()->back()->with('success', 'Unit created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $unit = Unit::findOrFail($id);
        $data = $request->all();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['updater_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['updater_type'] = 'admin';
        }
        $unit->update($data);

        return redirect()->back()->with('success', 'Unit updated successfully.');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->back()->with('success', 'Unit deleted successfully.');
    }
}
