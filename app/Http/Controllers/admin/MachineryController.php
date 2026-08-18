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
    private function applyCoordinatorFilter($query)
    {
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
            $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
            if ($coordinator && $coordinator->assigned_sites_ids) {
                $assignedSites = $coordinator->assigned_sites_ids;
                $query->where(function($q) use ($assignedSites) {
                    $q->whereHas('transfers', function($sq) use ($assignedSites) {
                        $sq->whereIn('to_site_id', $assignedSites)
                           ->where('id', function($subQuery) {
                               $subQuery->select('id')->from('transfers')->whereColumn('machinery_id', 'machinaries.id')->orderByDesc('id')->limit(1);
                           });
                    })->orWhereDoesntHave('transfers');
                });
            } else {
                $query->where('id', 0);
            }
        }
        return $query;
    }
    // --- Machine Categories ---
    public function machineCategoryView(Request $request)
    {
        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                MachineCategory::latest(),
                'machine_categories_export.xlsx',
                function ($category) {
                    return [
                        'Name' => $category->name,
                        'Status' => $category->status ? 'Active' : 'Inactive',
                        'Created At' => $category->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            );
        }

        $categories = MachineCategory::latest()->get();
        return view('admin.machinery.machine-category', compact('categories'));
    }

    public function machineCategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:machine_categories,name',
        ]);

        $data = $request->all();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['creator_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['creator_type'] = 'admin';
        }

        MachineCategory::create($data);

        return redirect()->back()->with('success', 'Machine Category created successfully.');
    }

    public function machineCategoryUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:machine_categories,name,' . $id,
        ]);

        $category = MachineCategory::findOrFail($id);
        
        $data = $request->all();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['updater_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['updater_type'] = 'admin';
        }

        $category->update($data);

        return redirect()->back()->with('success', 'Machine Category updated successfully.');
    }

    public function machineCategoryDelete($id)
    {
        $category = MachineCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Machine Category deleted successfully.');
    }

    // --- Machineries ---
    public function addMachineryView(Request $request)
    {
        $categories = MachineCategory::where('status', true)->get();
        
        $query = Machinary::with('category');
        $query = $this->applyCoordinatorFilter($query);

        // Search Filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('machine_code', 'like', '%' . $request->search . '%');
            });
        }

        // Category Filter
        if ($request->category_id) {
            $query->where('machine_category_id', $request->category_id);
        }

        // Condition Filter
        if ($request->condition) {
            $query->where('condition', $request->condition);
        }

        // Date Filter
        if ($request->date) {
            $query->whereDate('entry_date', $request->date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'machineries_export.xlsx',
                function ($machinery) {
                    return [
                        'Date' => \Carbon\Carbon::parse($machinery->entry_date)->format('M d, Y'),
                        'Code' => $machinery->machine_code,
                        'Name' => $machinery->name,
                        'Category' => $machinery->category->name ?? 'N/A',
                        'Condition' => ucfirst($machinery->condition),
                    ];
                }
            );
        }

        $machineries = $query->latest()->paginate(10)->withQueryString();

        return view('admin.machinery.add-machinery', compact('categories', 'machineries'));
    }

    public function machineryBulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) {
            return redirect()->back()->with('error', 'No assets selected for deletion.');
        }

        $machineries = Machinary::whereIn('id', $ids)->get();
        foreach ($machineries as $machine) {
            if ($machine->image) {
                Storage::disk('public')->delete($machine->image);
            }
            $machine->delete();
        }

        return redirect()->back()->with('success', 'Selected assets deleted successfully.');
    }

    public function machineryStore(Request $request)
    {
        $request->validate([
            'machine_category_id' => 'required|exists:machine_categories,id',
            'name' => 'required|string|max:255',
            'machine_code' => 'required|string|max:255|unique:machinaries,machine_code',
            'entry_date' => 'required|date',
            'condition' => 'required|in:running,repair,damage,missing',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('machinery', 'public');
        }

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['creator_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['creator_type'] = 'admin';
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
            'condition' => 'required|in:running,repair,damage,missing',
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

        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['updater_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['updater_type'] = 'admin';
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
    public function transferMachineryView(Request $request)
    {
        $machineryQuery = Machinary::where('status', true)->where('condition', 'running');
        $machineryQuery = $this->applyCoordinatorFilter($machineryQuery);
        $machineries = $machineryQuery->get();
        $sites = WorkingSite::all();
        
        $query = Transfer::with(['machinery', 'fromSite', 'toSite']);

        // Search Filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('machinery', function($mq) use ($request) {
                    $mq->where('name', 'like', '%' . $request->search . '%')
                       ->orWhere('machine_code', 'like', '%' . $request->search . '%');
                })->orWhereHas('toSite', function($sq) use ($request) {
                    $sq->where('site_name', 'like', '%' . $request->search . '%');
                });
            });
        }

        // Machinery Filter
        if ($request->machinery_id) {
            $query->where('machinery_id', $request->machinery_id);
        }

        // Site Filter
        if ($request->site_id) {
            $query->where('to_site_id', $request->site_id);
        }

        // Date Filter
        if ($request->date) {
            $query->whereDate('transfer_date', $request->date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'transfers_export.xlsx',
                function ($transfer) {
                    return [
                        'Date' => \Carbon\Carbon::parse($transfer->transfer_date)->format('M d, Y'),
                        'Machinery' => $transfer->machinery->name ?? 'N/A',
                        'Code' => $transfer->machinery->machine_code ?? 'N/A',
                        'From Site' => $transfer->fromSite->site_name ?? 'N/A',
                        'To Site' => $transfer->toSite->site_name ?? 'N/A',
                        'Status' => ucfirst($transfer->status),
                        'Remarks' => $transfer->remarks,
                    ];
                }
            );
        }

        $transfers = $query->latest()->paginate(10)->withQueryString();

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
    public function workingSitesView(Request $request)
    {
        $query = WorkingSite::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('site_name', 'like', '%' . $request->search . '%')
                  ->orWhere('site_code', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'working_sites_export.xlsx',
                function ($site) {
                    return [
                        'Code' => $site->site_code,
                        'Name' => $site->site_name,
                        'Location' => $site->location,
                        'Created At' => $site->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            );
        }

        $sites = $query->latest()->paginate(10)->withQueryString();
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

    // --- Damaged Machinery ---
    public function damagedMachineryView(Request $request)
    {
        $categories = MachineCategory::where('status', true)->get();
        
        $query = Machinary::with('category')->where('condition', 'damage');
        $query = $this->applyCoordinatorFilter($query);

        // Search Filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('machine_code', 'like', '%' . $request->search . '%');
            });
        }

        // Category Filter
        if ($request->category_id) {
            $query->where('machine_category_id', $request->category_id);
        }

        // Date Filter
        if ($request->date) {
            $query->whereDate('entry_date', $request->date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'damaged_machinery_export.xlsx',
                function ($machinery) {
                    return [
                        'Date' => \Carbon\Carbon::parse($machinery->entry_date)->format('M d, Y'),
                        'Code' => $machinery->machine_code,
                        'Name' => $machinery->name,
                        'Category' => $machinery->category->name ?? 'N/A',
                    ];
                }
            );
        }

        $machineries = $query->latest()->paginate(10)->withQueryString();

        return view('admin.machinery.damaged.index', compact('categories', 'machineries'));
    }

    public function damagedMachineryShow($id)
    {
        $machinery = Machinary::with(['category', 'transfers.fromSite', 'transfers.toSite'])->findOrFail($id);
        return view('admin.machinery.damaged.show', compact('machinery'));
    }

    // --- Running Machinery ---
    public function runningMachineryView(Request $request)
    {
        $categories = MachineCategory::where('status', true)->get();
        $query = Machinary::with('category')->where('condition', 'running');
        $query = $this->applyCoordinatorFilter($query);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('machine_code', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->category_id) {
            $query->where('machine_category_id', $request->category_id);
        }
        if ($request->date) {
            $query->whereDate('entry_date', $request->date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'running_machinery_export.xlsx',
                function ($machinery) {
                    return [
                        'Date' => \Carbon\Carbon::parse($machinery->entry_date)->format('M d, Y'),
                        'Code' => $machinery->machine_code,
                        'Name' => $machinery->name,
                        'Category' => $machinery->category->name ?? 'N/A',
                    ];
                }
            );
        }

        $machineries = $query->latest()->paginate(10)->withQueryString();
        return view('admin.machinery.running.index', compact('categories', 'machineries'));
    }

    public function runningMachineryShow($id)
    {
        $machinery = Machinary::with(['category', 'transfers.fromSite', 'transfers.toSite'])->findOrFail($id);
        return view('admin.machinery.running.show', compact('machinery'));
    }

    // --- Repair Machinery ---
    public function repairMachineryView(Request $request)
    {
        $categories = MachineCategory::where('status', true)->get();
        $query = Machinary::with('category')->where('condition', 'repair');
        $query = $this->applyCoordinatorFilter($query);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('machine_code', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->category_id) {
            $query->where('machine_category_id', $request->category_id);
        }
        if ($request->date) {
            $query->whereDate('entry_date', $request->date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'repair_machinery_export.xlsx',
                function ($machinery) {
                    return [
                        'Date' => \Carbon\Carbon::parse($machinery->entry_date)->format('M d, Y'),
                        'Code' => $machinery->machine_code,
                        'Name' => $machinery->name,
                        'Category' => $machinery->category->name ?? 'N/A',
                    ];
                }
            );
        }

        $machineries = $query->latest()->paginate(10)->withQueryString();
        return view('admin.machinery.repair.index', compact('categories', 'machineries'));
    }

    public function repairMachineryShow($id)
    {
        $machinery = Machinary::with(['category', 'transfers.fromSite', 'transfers.toSite'])->findOrFail($id);
        return view('admin.machinery.repair.show', compact('machinery'));
    }

    // --- Missing Machinery ---
    public function missingMachineryView(Request $request)
    {
        $categories = MachineCategory::where('status', true)->get();
        $query = Machinary::with('category')->where('condition', 'missing');
        $query = $this->applyCoordinatorFilter($query);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('machine_code', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->category_id) {
            $query->where('machine_category_id', $request->category_id);
        }
        if ($request->date) {
            $query->whereDate('entry_date', $request->date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'missing_machinery_export.xlsx',
                function ($machinery) {
                    return [
                        'Date' => \Carbon\Carbon::parse($machinery->entry_date)->format('M d, Y'),
                        'Code' => $machinery->machine_code,
                        'Name' => $machinery->name,
                        'Category' => $machinery->category->name ?? 'N/A',
                    ];
                }
            );
        }

        $machineries = $query->latest()->paginate(10)->withQueryString();
        return view('admin.machinery.missing.index', compact('categories', 'machineries'));
    }

    public function missingMachineryShow($id)
    {
        $machinery = Machinary::with(['category', 'transfers.fromSite', 'transfers.toSite'])->findOrFail($id);
        return view('admin.machinery.missing.show', compact('machinery'));
    }
}
