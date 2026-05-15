@extends('admin.layouts.app')

@section('title', 'Edit ' . ucfirst($role))

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit {{ ucfirst($role) }}</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.hrmanagement.index', ['role' => $role]) }}">HR Management</a></li>
                    <li class="breadcrumb-item" aria-current="page">Edit {{ ucfirst($role) }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ ucfirst($role) }} Information - {{ $user->code }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.hrmanagement.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Basic Info -->
                        <div class="col-md-4">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        @if($role != 'worker')
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Password <small>(Leave blank to keep current)</small></label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        @endif

                        <div class="col-md-4">
                            <label class="form-label">Mobile <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $user->joining_date) }}" required>
                        </div>

                        @if($role == 'worker')
                        <div class="col-md-4">
                            <label class="form-label">Work Skill</label>
                            <select name="work_skill_id" class="form-select select2">
                                <option value="">Select Skill</option>
                                @foreach($skills as $skill)
                                <option value="{{ $skill->id }}" {{ old('work_skill_id', $user->work_skill_id) == $skill->id ? 'selected' : '' }}>{{ $skill->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @if($role != 'worker' && $role != 'hr')
                        <div class="col-md-4">
                            <label class="form-label">Designation</label>
                            <select name="designation_id" class="form-select select2">
                                <option value="">Select Designation</option>
                                @foreach($designations as $desig)
                                <option value="{{ $desig->id }}" {{ old('designation_id', $user->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @elseif($role == 'hr')
                        <div class="col-md-4">
                            <label class="form-label">Designation</label>
                            <input type="text" class="form-control" value="HR" disabled>
                            <input type="hidden" name="designation_id" value="{{ $user->designation_id }}">
                        </div>
                        @endif

                        <div class="col-md-4">
                            <label class="form-label">Working Site</label>
                            <select name="working_site_id" class="form-select select2">
                                <option value="">Select Site</option>
                                @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ old('working_site_id', $user->working_site_id) == $site->id ? 'selected' : '' }}>{{ $site->site_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="current_address" class="form-control" rows="2">{{ old('current_address', $user->current_address) }}</textarea>
                        </div>

                        <!-- Statutory Info -->
                        <div class="col-md-4">
                            <label class="form-label">ESI No</label>
                            <input type="text" name="esi_no" class="form-control" value="{{ old('esi_no', $user->esi_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">PF No</label>
                            <input type="text" name="pf_no" class="form-control" value="{{ old('pf_no', $user->pf_no) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bank A/C</label>
                            <input type="text" name="bank_account_no" class="form-control" value="{{ old('bank_account_no', $user->bank_account_no) }}">
                        </div>

                        <!-- Files -->
                        <div class="col-md-4">
                            <label class="form-label">Profile Image</label>
                            @if($user->profile_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$user->profile_image) }}" width="50" height="50" class="rounded">
                            </div>
                            @endif
                            <input type="file" name="profile_image" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">PAN Card (Image/PDF)</label>
                            @if($user->pancard)
                            <div class="mb-2">
                                <a href="{{ asset('storage/'.$user->pancard) }}" target="_blank" class="btn btn-xs btn-info py-1">View Current PAN</a>
                            </div>
                            @endif
                            <input type="file" name="pancard" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Aadhar Card (Image/PDF)</label>
                            @if($user->adhaarcard)
                            <div class="mb-2">
                                <a href="{{ asset('storage/'.$user->adhaarcard) }}" target="_blank" class="btn btn-xs btn-info py-1">View Current Aadhar</a>
                            </div>
                            @endif
                            <input type="file" name="adhaarcard" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('admin.hrmanagement.index', ['role' => $role]) }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update {{ ucfirst($role) }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
