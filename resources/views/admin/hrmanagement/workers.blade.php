@extends('admin.layouts.app')

@section('title', 'All Workers')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-10">
                <div class="page-header-title">
                    <h5 class="m-b-10">All Workers</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0)">HR Management</a></li>
                    <li class="breadcrumb-item" aria-current="page">View Workers</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-header-actions col-md-2 text-end">
        <button class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal"
            data-bs-target="#addUserModal">
            <i class="ti ti-plus me-2"></i><span>Add Worker</span>
        </button>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Workers</h5>
                <form action="{{ route('admin.hrmanagement.workers') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('admin.hrmanagement.workers') }}" class="btn btn-secondary btn-sm">Reset</a>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Joining Date</th>
                                <th>Worker Name</th>
                                <th>Mobile</th>
                                <th>Code</th>
                                <th>Skill</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ ($users->currentPage()-1) * $users->perPage() + $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->joining_date)->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/images/logo.gif') }}" class="rounded-circle me-2" width="30" height="30">
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->mobile }}</td>
                                <td><span class="badge bg-light-primary text-primary">{{ $user->code }}</span></td>
                                <td>{{ $user->skill->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $user->status == 'active' ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-light-info border" title="View" data-bs-toggle="modal" data-bs-target="#viewUserModal{{ $user->id }}">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light-warning border" title="Edit" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light-danger border" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Worker Details - {{ $user->code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-4 text-center">
                                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('assets/images/logo.gif') }}" class="img-fluid rounded mb-2" style="max-height: 200px;">
                                                </div>
                                                <div class="col-md-8">
                                                    <table class="table table-sm">
                                                        <tr><th>Name:</th><td>{{ $user->name }}</td></tr>
                                                        <tr><th>Email:</th><td>{{ $user->email }}</td></tr>
                                                        <tr><th>Mobile:</th><td>{{ $user->mobile }}</td></tr>
                                                        <tr><th>Role:</th><td>{{ ucfirst($user->role) }}</td></tr>
                                                        <tr><th>Joining Date:</th><td>{{ $user->joining_date }}</td></tr>
                                                        <tr><th>Skill:</th><td>{{ $user->skill->name ?? 'N/A' }}</td></tr>
                                                        <tr><th>Designation:</th><td>{{ $user->designation->name ?? 'N/A' }}</td></tr>
                                                        <tr><th>Working Site:</th><td>{{ $user->site->site_name ?? 'N/A' }}</td></tr>
                                                        <tr><th>Address:</th><td>{{ $user->current_address }}</td></tr>
                                                        <tr><th>ESI No:</th><td>{{ $user->esi_no ?? 'N/A' }}</td></tr>
                                                        <tr><th>PF No:</th><td>{{ $user->pf_no ?? 'N/A' }}</td></tr>
                                                        <tr><th>Bank A/C:</th><td>{{ $user->bank_account_no ?? 'N/A' }}</td></tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6 text-center">
                                                    <label class="fw-bold d-block">PAN Card</label>
                                                    @if($user->pancard)
                                                        <a href="{{ asset('storage/'.$user->pancard) }}" target="_blank" class="btn btn-sm btn-info mt-1">View PAN</a>
                                                    @else
                                                        <span class="text-muted">Not Uploaded</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 text-center">
                                                    <label class="fw-bold d-block">Aadhar Card</label>
                                                    @if($user->adhaarcard)
                                                        <a href="{{ asset('storage/'.$user->adhaarcard) }}" target="_blank" class="btn btn-sm btn-info mt-1">View Aadhar</a>
                                                    @else
                                                        <span class="text-muted">Not Uploaded</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <form action="{{ route('admin.hrmanagement.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Worker</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Password <small>(Leave blank to keep current)</small></label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Mobile <span class="text-danger">*</span></label>
                                                <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Joining Date <span class="text-danger">*</span></label>
                                                <input type="date" name="joining_date" class="form-control" value="{{ $user->joining_date }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Work Skill</label>
                                                <select name="work_skill_id" class="form-select">
                                                    <option value="">Select Skill</option>
                                                    @foreach($skills as $skill)
                                                    <option value="{{ $skill->id }}" {{ $user->work_skill_id == $skill->id ? 'selected' : '' }}>{{ $skill->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Designation</label>
                                                <select name="designation_id" class="form-select">
                                                    <option value="">Select Designation</option>
                                                    @foreach($designations as $desig)
                                                    <option value="{{ $desig->id }}" {{ $user->designation_id == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Working Site</label>
                                                <select name="working_site_id" class="form-select">
                                                    <option value="">Select Site</option>
                                                    @foreach($sites as $site)
                                                    <option value="{{ $site->id }}" {{ $user->working_site_id == $site->id ? 'selected' : '' }}>{{ $site->site_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Address</label>
                                                <textarea name="current_address" class="form-control" rows="2">{{ $user->current_address }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">ESI No</label>
                                                <input type="text" name="esi_no" class="form-control" value="{{ $user->esi_no }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">PF No</label>
                                                <input type="text" name="pf_no" class="form-control" value="{{ $user->pf_no }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Bank A/C</label>
                                                <input type="text" name="bank_account_no" class="form-control" value="{{ $user->bank_account_no }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Profile Image</label>
                                                <input type="file" name="profile_image" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">PAN Card</label>
                                                <input type="file" name="pancard" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Aadhar Card</label>
                                                <input type="file" name="adhaarcard" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.hrmanagement.users.destroy', $user->id) }}" method="POST" class="modal-content">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Worker</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete worker <b>{{ $user->name }}</b>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Custom Pagination -->
                @if($users->hasPages())
                <div class="custom-pagination mt-3">
                    <a href="{{ $users->previousPageUrl() }}" class="btn-nav {{ $users->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    
                    <div class="page-input-group">
                        <input type="number" value="{{ $users->currentPage() }}" min="1" max="{{ $users->lastPage() }}" id="goto-page">
                        <span>/ {{ $users->lastPage() }}</span>
                    </div>

                    <a href="{{ $users->nextPageUrl() }}" class="btn-nav {{ $users->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Pagination Goto Logic
        const gotoInput = document.getElementById('goto-page');
        if (gotoInput) {
            gotoInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const page = gotoInput.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('page', page);
                    window.location.href = url.href;
                }
            });
        }
    });
</script>
@endpush

<!-- Add Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('admin.hrmanagement.users.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <input type="hidden" name="role" value="worker">
            <div class="modal-header">
                <h5 class="modal-title">Add New Worker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mobile <span class="text-danger">*</span></label>
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Joining Date <span class="text-danger">*</span></label>
                    <input type="date" name="joining_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Work Skill</label>
                    <select name="work_skill_id" class="form-select">
                        <option value="">Select Skill</option>
                        @foreach($skills as $skill)
                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Designation</label>
                    <select name="designation_id" class="form-select">
                        <option value="">Select Designation</option>
                        @foreach($designations as $desig)
                        <option value="{{ $desig->id }}">{{ $desig->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Working Site</label>
                    <select name="working_site_id" class="form-select">
                        <option value="">Select Site</option>
                        @foreach($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->site_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <textarea name="current_address" class="form-control" rows="2" placeholder="Current Address"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ESI No</label>
                    <input type="text" name="esi_no" class="form-control" placeholder="ESI Number">
                </div>
                <div class="col-md-4">
                    <label class="form-label">PF No</label>
                    <input type="text" name="pf_no" class="form-control" placeholder="PF Number">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bank A/C</label>
                    <input type="text" name="bank_account_no" class="form-control" placeholder="Bank Details">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">PAN Card</label>
                    <input type="file" name="pancard" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Aadhar Card</label>
                    <input type="file" name="adhaarcard" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save Worker</button>
            </div>
        </form>
    </div>
</div>
@endsection
