@extends('admin.layouts.app')

@section('title', 'HR Management - ' . ucfirst(Str::plural($role)))

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-10">
                <div class="page-header-title">
                    <h5 class="m-b-10">HR Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0)">HR Management</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ ucfirst(Str::plural($role)) }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-header-actions col-md-2 text-end">
        <a href="{{ route('admin.hrmanagement.create', ['role' => $role]) }}" class="btn btn-success btn-sm fw-bold">
            <i class="ti ti-plus me-2"></i><span>Add {{ ucfirst($role) }}</span>
        </a>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<div class="row">
    <div class="col-sm-12">
        <!-- Role Tabs -->
        <div class="card mb-3">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link {{ $role == 'worker' ? 'active' : '' }}" href="{{ route('admin.hrmanagement.index', ['role' => 'worker']) }}">Workers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $role == 'supervisor' ? 'active' : '' }}" href="{{ route('admin.hrmanagement.index', ['role' => 'supervisor']) }}">Supervisors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $role == 'staff' ? 'active' : '' }}" href="{{ route('admin.hrmanagement.index', ['role' => 'staff']) }}">Staffs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $role == 'hr' ? 'active' : '' }}" href="{{ route('admin.hrmanagement.index', ['role' => 'hr']) }}">HR</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>All {{ ucfirst(Str::plural($role)) }}</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route('admin.hrmanagement.index') }}" method="GET" class="row mb-4">
                    <input type="hidden" name="role" value="{{ $role }}">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Code, Name, Mobile..." value="{{ request('search') }}">
                    </div>
                    @if($role == 'worker')
                    <div class="col-md-2 mb-2">
                        <select name="work_skill_id" class="form-control select2" data-placeholder="All Skills">
                            <option value=""></option>
                            @foreach($skills as $skill)
                            <option value="{{ $skill->id }}" {{ request('work_skill_id') == $skill->id ? 'selected' : '' }}>{{ $skill->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="col-md-2 mb-2">
                        <select name="designation_id" class="form-control select2" data-placeholder="All Designations">
                            <option value=""></option>
                            @foreach($designations as $desig)
                            <option value="{{ $desig->id }}" {{ request('designation_id') == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-2 mb-2">
                        <select name="working_site_id" class="form-control select2" data-placeholder="All Sites">
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('working_site_id') == $site->id ? 'selected' : '' }}>{{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="joining_date" class="form-control" value="{{ request('joining_date') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('admin.hrmanagement.index', ['role' => $role]) }}" class="btn btn-light flex-grow-1 border">Clear</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>SL</th>
                                <th>Joining Date</th>
                                <th>{{ ucfirst($role) }} Name</th>
                                <th>Mobile</th>
                                <th>Code</th>
                                @if($role == 'worker')
                                <th>Skill</th>
                                @else
                                <th>Designation</th>
                                @endif
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
                                @if($role == 'worker')
                                <td>{{ $user->skill->name ?? 'N/A' }}</td>
                                @else
                                <td>{{ $user->designation->name ?? 'N/A' }}</td>
                                @endif
                                <td>
                                    <span class="badge {{ $user->status == 'active' ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.hrmanagement.show', $user->id) }}" class="btn btn-sm btn-icon btn-light-info" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.hrmanagement.edit', $user->id) }}" class="btn btn-sm btn-icon btn-light-primary" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" title="Delete" data-url="{{ route('admin.hrmanagement.destroy', $user->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
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

<!-- Global Delete Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <i class="ti ti-alert-triangle text-danger" style="font-size: 3.5rem;"></i>
                </div>
                <h4 class="mb-2">Are you sure?</h4>
                <p class="text-muted" id="delete-modal-msg">Deleting this {{ $role }} will remove them permanently!</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger fw-bold" id="confirm-delete-btn">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const deleteForm = document.getElementById('deleteForm');
        const deleteMsg = document.getElementById('delete-modal-msg');

        // Single Delete
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                deleteForm.setAttribute('action', url);
                deleteModal.show();
            });
        });

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
