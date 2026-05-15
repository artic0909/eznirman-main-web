@extends('admin.layouts.app')

@section('title', 'Designations')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-10">
                <div class="page-header-title">
                    <h5 class="m-b-10">All Designations</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript: void(0)">HR Management</a></li>
                    <li class="breadcrumb-item" aria-current="page">View Designations</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-header-actions col-md-2 text-end">
        <button class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal"
            data-bs-target="#addDesignationModal">
            <i class="ti ti-plus me-2"></i><span>Add Data</span>
        </button>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Designations</h5>
                <div class="d-flex gap-3">
                    <form action="{{ route('admin.hrmanagement.designations.bulk-action') }}" method="POST" id="bulkActionForm">
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
                                <th>Designation</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designations as $index => $designation)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input row-checkbox" type="checkbox" form="bulkActionForm" name="ids[]" value="{{ $designation->id }}">
                                    </div>
                                </td>
                                <td>{{ ($designations->currentPage()-1) * $designations->perPage() + $index + 1 }}</td>
                                <td>{{ $designation->created_at->format('M d, Y') }}</td>
                                <td>{{ $designation->name }}</td>
                                <td>
                                    <span class="badge {{ $designation->status == 'active' ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}">
                                        {{ ucfirst($designation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-light-warning border" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#editDesignationModal{{ $designation->id }}">
                                            <i class="ti ti-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light-danger border" title="Delete"
                                            data-bs-toggle="modal" data-bs-target="#deleteDesignationModal{{ $designation->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editDesignationModal{{ $designation->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <form action="{{ route('admin.hrmanagement.designations.update', $designation->id) }}" method="POST" class="modal-content">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Designation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Designation<span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ $designation->name }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Status<span class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="active" {{ $designation->status == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $designation->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                            <div class="modal fade" id="deleteDesignationModal{{ $designation->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.hrmanagement.designations.destroy', $designation->id) }}" method="POST" class="modal-content">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Designation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this designation?</p>
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
                @if($designations->hasPages())
                <div class="custom-pagination mt-3">
                    <a href="{{ $designations->previousPageUrl() }}" class="btn-nav {{ $designations->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    
                    <div class="page-input-group">
                        <input type="number" value="{{ $designations->currentPage() }}" min="1" max="{{ $designations->lastPage() }}" id="goto-page">
                        <span>/ {{ $designations->lastPage() }}</span>
                    </div>

                    <a href="{{ $designations->nextPageUrl() }}" class="btn-nav {{ $designations->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDesignationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.hrmanagement.designations.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Designation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Designation<span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Enter designation name" required>
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
