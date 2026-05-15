@extends('admin.layouts.app')

@section('title', 'Skills')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-10">
                <div class="page-header-title">
                    <h5 class="m-b-10">All Skills</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0)">HR Management</a></li>
                    <li class="breadcrumb-item" aria-current="page">View Skills</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-header-actions col-md-2 text-end">
        <button class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal"
            data-bs-target="#addSkillModal">
            <i class="ti ti-plus me-2"></i><span>Add Data</span>
        </button>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Skills</h5>
                <div class="d-flex gap-3">
                    <form action="{{ route('admin.hrmanagement.skills.bulk-action') }}" method="POST" id="bulkActionForm">
                        @csrf
                        <input type="hidden" name="action" id="bulkActionValue">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                id="bulkActionDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                disabled>
                                <i class="ti ti-settings me-1"></i>
                                Bulk Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="bulkActionDropdown">
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="performBulkAction('delete')">
                                        <i class="ti ti-trash me-2"></i>Delete Selected
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-success" href="#" onclick="performBulkAction('active')">
                                        <i class="ti ti-check me-2"></i>Active Selected
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-warning" href="#" onclick="performBulkAction('inactive')">
                                        <i class="ti ti-x me-2"></i>Inactive Selected
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>SL</th>
                                <th>Creation Date</th>
                                <th>Skill</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skills as $index => $skill)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input row-checkbox" type="checkbox" form="bulkActionForm" name="ids[]" value="{{ $skill->id }}">
                                    </div>
                                </td>
                                <td>{{ ($skills->currentPage()-1) * $skills->perPage() + $index + 1 }}</td>
                                <td>{{ $skill->created_at->format('M d, Y') }}</td>
                                <td>{{ $skill->name }}</td>
                                <td>
                                    <span class="badge {{ $skill->status == 'active' ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                        {{ ucfirst($skill->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-light-warning border" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#editSkillModal{{ $skill->id }}">
                                            <i class="ti ti-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light-danger border" title="Delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteSkillModal{{ $skill->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSkillModal{{ $skill->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <form action="{{ route('admin.hrmanagement.skills.update', $skill->id) }}" method="POST" class="modal-content">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Skill</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Skill<span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $skill->name }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Status<span class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" {{ $skill->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $skill->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
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
                            <div class="modal fade" id="deleteSkillModal{{ $skill->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.hrmanagement.skills.destroy', $skill->id) }}" method="POST" class="modal-content">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Skill</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this skill?</p>
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
                @if($skills->hasPages())
                <div class="custom-pagination mt-3">
                    <a href="{{ $skills->previousPageUrl() }}" class="btn-nav {{ $skills->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    
                    <div class="page-input-group">
                        <input type="number" value="{{ $skills->currentPage() }}" min="1" max="{{ $skills->lastPage() }}" id="goto-page">
                        <span>/ {{ $skills->lastPage() }}</span>
                    </div>

                    <a href="{{ $skills->nextPageUrl() }}" class="btn-nav {{ $skills->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.hrmanagement.skills.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Skill<span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Enter skill name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status<span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActionButton();
    });

    document.querySelectorAll('.row-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const allCheckboxes = document.querySelectorAll('.row-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            document.getElementById('selectAll').checked = allCheckboxes.length === checkedCheckboxes.length;
            toggleBulkActionButton();
        });
    });

    function toggleBulkActionButton() {
        const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const bulkActionBtn = document.getElementById('bulkActionDropdown');
        bulkActionBtn.disabled = checkedCheckboxes.length === 0;
    }

    function performBulkAction(action) {
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete selected items?')) return;
        }
        document.getElementById('bulkActionValue').value = action;
        document.getElementById('bulkActionForm').submit();
    }

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
