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
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>All Designations</h5>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-danger btn-sm fw-bold d-none" id="btn-bulk-delete">
                            <i class="ti ti-trash"></i> Bulk Delete (<span id="selected-count">0</span>)
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route('admin.hrmanagement.designations.index') }}" method="GET" class="row mb-4">
                    <div class="col-md-9 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search designation name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('admin.hrmanagement.designations.index') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                        <button type="submit" name="export" value="excel" class="btn btn-success flex-grow-1"><i class="ti ti-table-export"></i></button>
                    </div>
                </form>
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
                                        <button class="btn btn-sm btn-icon btn-light-primary" title="Edit"
                                            data-bs-toggle="modal" data-bs-target="#editDesignationModal{{ $designation->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" title="Delete"
                                            data-url="{{ route('admin.hrmanagement.designations.destroy', $designation->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="editDesignationModal{{ $designation->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title text-white">Edit Designation</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.hrmanagement.designations.update', $designation->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
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

<div class="modal fade" id="addDesignationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">Add Designation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hrmanagement.designations.store') }}" method="POST">
                @csrf
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
                <p class="text-muted" id="delete-modal-msg">Deleting this item will remove it permanently!</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    <input type="hidden" name="ids[]" id="bulk-ids">
                    <div id="method-container"></div>
                    <button type="submit" class="btn btn-danger fw-bold" id="confirm-delete-btn">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const deleteForm = document.getElementById('deleteForm');
        const methodContainer = document.getElementById('method-container');
        const deleteMsg = document.getElementById('delete-modal-msg');
        const bulkIdsInput = document.getElementById('bulk-ids');

        // Single Delete
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                deleteForm.setAttribute('action', url);
                methodContainer.innerHTML = '<input type="hidden" name="_method" value="DELETE">';
                deleteMsg.innerText = "Deleting this designation will remove it permanently!";
                bulkIdsInput.value = "";
                deleteModal.show();
            });
        });

        // Bulk Selection Logic
        const checkAll = document.getElementById('selectAll');
        const checkItems = document.querySelectorAll('.row-checkbox');
        const bulkDeleteBtn = document.getElementById('btn-bulk-delete');
        const selectedCount = document.getElementById('selected-count');

        const updateBulkUI = () => {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            selectedCount.innerText = checkedCount;
            if (checkedCount > 0) {
                bulkDeleteBtn.classList.remove('d-none');
            } else {
                bulkDeleteBtn.classList.add('d-none');
            }
        };

        checkAll.addEventListener('change', function() {
            checkItems.forEach(item => item.checked = this.checked);
            updateBulkUI();
        });

        checkItems.forEach(item => {
            item.addEventListener('change', updateBulkUI);
        });

        // Bulk Delete Action
        bulkDeleteBtn.addEventListener('click', () => {
            const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
            deleteForm.setAttribute('action', "{{ route('admin.hrmanagement.designations.bulk-action') }}");
            methodContainer.innerHTML = '<input type="hidden" name="action" value="delete">'; 
            deleteMsg.innerText = `Are you sure you want to delete ${selectedIds.length} selected designations?`;
            
            // Clear existing hidden inputs for ids and add selected IDs
            deleteForm.querySelectorAll('input[name="ids[]"]').forEach(el => {
                if(el.id !== 'bulk-ids') el.remove();
            });
            
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                deleteForm.appendChild(input);
            });

            deleteModal.show();
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
