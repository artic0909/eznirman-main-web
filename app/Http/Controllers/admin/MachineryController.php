<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MachineCategory;
use App\Models\Machinary;
use App\Models\WorkingSite;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MachineryController extends Controller
{
    // --- Machine Categories ---
    public function machineCategoryView()
    {
        $categories = MachineCategory::latest()->get();
        return view('admin.machinery.machine-category', compact('categories'));
    }

    public function machineCategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:machine_categories,name',
        ]);

        MachineCategory::create($request->all());

        return redirect()->back()->with('success', 'Machine Category created successfully.');
    }

    public function machineCategoryUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:machine_categories,name,' . $id,
        ]);

        $category = MachineCategory::findOrFail($id);
        $category->update($request->all());

        return redirect()->back()->with('success', 'Machine Category updated successfully.');
    }

    public function machineCategoryDelete($id)
    {
        $category = MachineCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Machine Category deleted successfully.');
    }

    // --- Machineries ---
    public function addMachineryView()
    {
        $categories = MachineCategory::where('status', true)->get();
        $machineries = Machinary::with('category')->latest()->get();
        return view('admin.machinery.add-machinery', compact('categories', 'machineries'));
    }

    public function machineryStore(Request $request)
    {
        $request->validate([
            'machine_category_id' => 'required|exists:machine_categories,id',
            'name' => 'required|string|max:255',
            'machine_code' => 'required|string|max:255|unique:machinaries,machine_code',
            'entry_date' => 'required|date',
            'condition' => 'required|in:running,repair,damage',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('machinery', 'public');
        }

        Machinary::create($data);

        return redirect()->back()->with('success', 'Machinery added successfully.');
    }

    public function machineryUpdate(Request $request, $id)
    {
        $request->validate([
            'machine_category_id' => 'required|exists:machine_categories,id',
            'name' => 'required|string|max:255',
            'machine_code' => 'required|string|max:255|unique:machinaries,machine_code,' . $id,
            'entry_date' => 'required|date',
            'condition' => 'required|in:running,repair,damage',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $machinery = Machinary::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($machinery->image) {
                Storage::disk('public')->delete($machinery->image);
            }
            $data['image'] = $request->file('image')->store('machinery', 'public');
        }

        $machinery->update($data);

        return redirect()->back()->with('success', 'Machinery updated successfully.');
    }

    public function machineryDelete($id)
    {
        $machinery = Machinary::findOrFail($id);
        if ($machinery->image) {
            Storage::disk('public')->delete($machinery->image);
        }
        $machinery->delete();

        return redirect()->back()->with('success', 'Machinery deleted successfully.');
    }

    // --- Transfers ---
    public function transferMachineryView()
    {
        $machineries = Machinary::where('status', true)->get();
        $sites = WorkingSite::where('status', true)->get();
        $transfers = Transfer::with(['machinery', 'fromSite', 'toSite'])->latest()->get();
        return view('admin.machinery.transfer-machinery', compact('machineries', 'sites', 'transfers'));
    }

    public function transferStore(Request $request)
    {
        $request->validate([
            'machinery_id' => 'required|exists:machinaries,id',
            'to_site_id' => 'required|exists:working_sites,id',
            'transfer_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $machinery = Machinary::findOrFail($request->machinery_id);
        
        // Get the latest transfer to determine the from_site
        $lastTransfer = Transfer::where('machinery_id', $machinery->id)
            ->where('status', 'completed')
            ->latest()
            ->first();

        $from_site_id = $lastTransfer ? $lastTransfer->to_site_id : null;

        if ($from_site_id == $request->to_site_id) {
            return redirect()->back()->with('error', 'Machinery is already at the target site.');
        }

        Transfer::create([
            'machinery_id' => $request->machinery_id,
            'from_site_id' => $from_site_id,
            'to_site_id' => $request->to_site_id,
            'transfer_date' => $request->transfer_date,
            'remarks' => $request->remarks,
            'status' => 'completed'
        ]);

        return redirect()->back()->with('success', 'Machinery transferred successfully.');
    }

    // --- Working Sites ---
    public function workingSitesView()
    {
        $sites = WorkingSite::latest()->get();
        return view('admin.machinery.working-sites', compact('sites'));
    }

    public function workingSiteStore(Request $request)
    {
        $request->validate([
            'site_code' => 'required|string|max:255|unique:working_sites,site_code',
            'site_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        WorkingSite::create($request->all());

        return redirect()->back()->with('success', 'Working Site created successfully.');
    }

    public function workingSiteUpdate(Request $request, $id)
    {
        $request->validate([
            'site_code' => 'required|string|max:255|unique:working_sites,site_code,' . $id,
            'site_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $site = WorkingSite::findOrFail($id);
        $site->update($request->all());

        return redirect()->back()->with('success', 'Working Site updated successfully.');
    }

    public function workingSiteDelete($id)
    {
        $site = WorkingSite::findOrFail($id);
        $site->delete();

        return redirect()->back()->with('success', 'Working Site deleted successfully.');
    }
}
