<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Skill;
use App\Models\User;
use App\Models\WorkingSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HRManagementController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'worker');
        $skills = Skill::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $sites = WorkingSite::all();

        $query = User::where('role', $role);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->work_skill_id) {
            $query->where('work_skill_id', $request->work_skill_id);
        }

        if ($request->designation_id) {
            $query->where('designation_id', $request->designation_id);
        }

        if ($request->working_site_id) {
            $query->where('working_site_id', $request->working_site_id);
        }

        if ($request->joining_date) {
            $query->whereDate('joining_date', $request->joining_date);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                $role . 's_export.xlsx',
                function ($user) use ($role) {
                    $row = [
                        'Joining Date' => \Carbon\Carbon::parse($user->joining_date)->format('M d, Y'),
                        'Code' => $user->code,
                        'Name' => $user->name,
                        'Mobile' => $user->mobile,
                    ];
                    
                    if ($role == 'worker') {
                        $row['Skill'] = $user->skill->name ?? 'N/A';
                    } else {
                        $row['Designation'] = $user->designation->name ?? 'N/A';
                    }
                    
                    $row['Status'] = ucfirst($user->status);
                    return $row;
                }
            );
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.hrmanagement.index', compact('users', 'skills', 'designations', 'sites', 'role'));
    }

    public function create(Request $request)
    {
        $role = $request->get('role', 'worker');
        $skills = Skill::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $sites = WorkingSite::all();
        
        return view('admin.hrmanagement.create', compact('role', 'skills', 'designations', 'sites'));
    }

    public function store(Request $request)
    {
        $role = $request->role;
        $rules = [
            'role' => 'required|in:worker,supervisor,staff,hr',
            'code' => 'required|string|unique:users,code',
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'joining_date' => 'required|date',
            'working_site_id' => 'nullable|exists:working_sites,id',
            'pancard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'adhaarcard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_image' => 'nullable|image|max:2048',
        ];

        if ($role != 'worker') {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6';
        }

        if ($role == 'worker') {
            $rules['work_skill_id'] = 'nullable|exists:skills,id';
        } else {
            $rules['designation_id'] = 'nullable|exists:designations,id';
        }

        $request->validate($rules);

        $data = $request->all();
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($role == 'hr') {
            $hrDesig = Designation::where('name', 'LIKE', '%HR%')->first();
            if ($hrDesig) {
                $data['designation_id'] = $hrDesig->id;
            }
        }
        


        if ($request->hasFile('pancard')) {
            $data['pancard'] = $request->file('pancard')->store('hrmanagement/pancards', 'public');
        }
        if ($request->hasFile('adhaarcard')) {
            $data['adhaarcard'] = $request->file('adhaarcard')->store('hrmanagement/adhaarcards', 'public');
        }
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('hrmanagement/profiles', 'public');
        }

        User::create($data);

        return redirect()->route('admin.hrmanagement.index', ['role' => $role])->with('success', ucfirst($role) . ' added successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.hrmanagement.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        $skills = Skill::where('status', 'active')->get();
        $designations = Designation::where('status', 'active')->get();
        $sites = WorkingSite::all();

        return view('admin.hrmanagement.edit', compact('user', 'role', 'skills', 'designations', 'sites'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;

        $rules = [
            'code' => 'required|string|unique:users,code,' . $id,
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'joining_date' => 'required|date',
            'working_site_id' => 'nullable|exists:working_sites,id',
            'pancard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'adhaarcard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_image' => 'nullable|image|max:2048',
        ];

        if ($role != 'worker') {
            $rules['email'] = 'required|email|unique:users,email,' . $id;
        }

        if ($role == 'worker') {
            $rules['work_skill_id'] = 'nullable|exists:skills,id';
        } else {
            $rules['designation_id'] = 'nullable|exists:designations,id';
        }

        $request->validate($rules);

        $data = $request->all();
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('pancard')) {
            if ($user->pancard) Storage::disk('public')->delete($user->pancard);
            $data['pancard'] = $request->file('pancard')->store('hrmanagement/pancards', 'public');
        }
        if ($request->hasFile('adhaarcard')) {
            if ($user->adhaarcard) Storage::disk('public')->delete($user->adhaarcard);
            $data['adhaarcard'] = $request->file('adhaarcard')->store('hrmanagement/adhaarcards', 'public');
        }
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) Storage::disk('public')->delete($user->profile_image);
            $data['profile_image'] = $request->file('profile_image')->store('hrmanagement/profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.hrmanagement.index', ['role' => $role])->with('success', ucfirst($role) . ' updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;

        if ($user->pancard) Storage::disk('public')->delete($user->pancard);
        if ($user->adhaarcard) Storage::disk('public')->delete($user->adhaarcard);
        if ($user->profile_image) Storage::disk('public')->delete($user->profile_image);
        
        $user->delete();

        return redirect()->back()->with('success', ucfirst($role) . ' deleted successfully.');
    }
}
