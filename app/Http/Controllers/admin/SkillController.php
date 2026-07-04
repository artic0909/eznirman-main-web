<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $query = Skill::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'skills_export.xlsx',
                function ($skill) {
                    return [
                        'Name' => $skill->name,
                        'Status' => ucfirst($skill->status),
                        'Created At' => $skill->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            );
        }

        $skills = $query->latest()->paginate(10)->withQueryString();
        return view('admin.hrmanagement.skills', compact('skills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'status' => 'required|in:active,inactive',
        ]);

        Skill::create($request->all());

        return redirect()->back()->with('success', 'Skill created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $skill = Skill::findOrFail($id);
        $skill->update($request->all());

        return redirect()->back()->with('success', 'Skill updated successfully.');
    }

    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return redirect()->back()->with('success', 'Skill deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $ids = $request->ids;
        $action = $request->action;

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        if ($action === 'delete') {
            Skill::whereIn('id', $ids)->delete();
            $msg = 'Selected skills deleted successfully.';
        } elseif ($action === 'active') {
            Skill::whereIn('id', $ids)->update(['status' => 'active']);
            $msg = 'Selected skills activated successfully.';
        } elseif ($action === 'inactive') {
            Skill::whereIn('id', $ids)->update(['status' => 'inactive']);
            $msg = 'Selected skills inactivated successfully.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
