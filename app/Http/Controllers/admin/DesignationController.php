<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        $query = Designation::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'designations_export.xlsx',
                function ($designation) {
                    return [
                        'Name' => $designation->name,
                        'Status' => ucfirst($designation->status),
                        'Created At' => $designation->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            );
        }

        $designations = $query->latest()->paginate(10)->withQueryString();
        return view('admin.hrmanagement.designations', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
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
        Designation::create($data);

        return redirect()->back()->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $designation = Designation::findOrFail($id);
        $data = $request->all();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['updater_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['updater_type'] = 'admin';
        }
        $designation->update($data);

        return redirect()->back()->with('success', 'Designation updated successfully.');
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return redirect()->back()->with('success', 'Designation deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $ids = $request->ids;
        $action = $request->action;

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        if ($action === 'delete') {
            Designation::whereIn('id', $ids)->delete();
            $msg = 'Selected designations deleted successfully.';
        } elseif ($action === 'active') {
            Designation::whereIn('id', $ids)->update(['status' => 'active']);
            $msg = 'Selected designations activated successfully.';
        } elseif ($action === 'inactive') {
            Designation::whereIn('id', $ids)->update(['status' => 'inactive']);
            $msg = 'Selected designations inactivated successfully.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
