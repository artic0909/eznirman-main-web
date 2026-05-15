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
    public function workers(Request $request)
    {
        return $this->index($request, 'worker');
    }

    public function supervisors(Request $request)
    {
        return $this->index($request, 'supervisor');
    }

    public function staffs(Request $request)
    {
        return $this->index($request, 'staff');
    }

    public function hrs(Request $request)
    {
        return $this->index($request, 'hr');
    }

    private function index(Request $request, $role)
    {
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

        $users = $query->latest()->paginate(10)->withQueryString();
        
        $viewName = 'admin.hrmanagement.' . Str::plural($role);
        // Map 'hr' to 'hrs' if needed, or check file names
        if ($role == 'hr') $viewName = 'admin.hrmanagement.hr';
        if ($role == 'staff') $viewName = 'admin.hrmanagement.staffs';
        if ($role == 'worker') $viewName = 'admin.hrmanagement.workers';
        if ($role == 'supervisor') $viewName = 'admin.hrmanagement.supervisors';

        return view($viewName, compact('users', 'skills', 'designations', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:worker,supervisor,staff,hr',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mobile' => 'required|string|max:15',
            'joining_date' => 'required|date',
            'work_skill_id' => 'nullable|exists:skills,id',
            'designation_id' => 'nullable|exists:designations,id',
            'working_site_id' => 'nullable|exists:working_sites,id',
            'pancard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'adhaarcard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        
        // Auto-generate code
        $lastUser = User::where('role', $request->role)->latest('id')->first();
        $nextId = $lastUser ? ((int)preg_replace('/[^0-9]/', '', $lastUser->code)) + 1 : 1;
        $prefix = strtoupper(substr($request->role, 0, 2));
        $data['code'] = $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);

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

        return redirect()->back()->with('success', ucfirst($request->role) . ' added successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|string|max:15',
            'joining_date' => 'required|date',
            'work_skill_id' => 'nullable|exists:skills,id',
            'designation_id' => 'nullable|exists:designations,id',
            'working_site_id' => 'nullable|exists:working_sites,id',
            'pancard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'adhaarcard' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'profile_image' => 'nullable|image|max:2048',
        ]);

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

        return redirect()->back()->with('success', ucfirst($user->role) . ' updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Delete files
        if ($user->pancard) Storage::disk('public')->delete($user->pancard);
        if ($user->adhaarcard) Storage::disk('public')->delete($user->adhaarcard);
        if ($user->profile_image) Storage::disk('public')->delete($user->profile_image);
        
        $role = $user->role;
        $user->delete();

        return redirect()->back()->with('success', ucfirst($role) . ' deleted successfully.');
    }
}
