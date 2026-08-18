@extends('admin.layouts.app')

@section('title', 'Measurement Units')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Unit Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Register</li>
                    <li class="breadcrumb-item" aria-current="page">Units</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Unit Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Add New Unit</h5>
            </div>
            <div class="card-body">
                <form action="{{ route(getRoutePrefix() . 'purchase.units.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. KG, Meter, Bag" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control select2" data-placeholder="Select Status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Unit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Units List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Measurement Units List</h5>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route(getRoutePrefix() . 'purchase.units.index') }}" method="GET" class="row mb-4">
                    <div class="col-md-9 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Unit Name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route(getRoutePrefix() . 'purchase.units.index') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                        <button type="submit" name="export" value="excel" class="btn btn-success flex-grow-1"><i class="ti ti-table-export"></i></button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Unit Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($units->currentPage() - 1) * $units->perPage() + 1;
                            @endphp
                            @forelse($units as $index => $unit)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td><span class="fw-bold">{{ $unit->name }}</span></td>
                                <td>
                                    @if($unit->status == 'active')
                                    <span class="badge bg-light-success text-success">Active</span>
                                    @else
                                    <span class="badge bg-light-danger text-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $unit->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route(getRoutePrefix() . 'purchase.units.destroy', $unit->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $unit->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Update Unit</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route(getRoutePrefix() . 'purchase.units.update', $unit->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Unit Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $unit->name }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-control select2-modal" data-placeholder="Select Status" required>
                                                                <option value="active" {{ $unit->status == 'active' ? 'selected' : '' }}>Active</option>
                                                                <option value="inactive" {{ $unit->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update Unit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">No measurement units found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($units->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $units->previousPageUrl() }}" class="btn-nav {{ $units->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    
                    <div class="page-input-group">
                        <input type="number" value="{{ $units->currentPage() }}" min="1" max="{{ $units->lastPage() }}" id="goto-page">
                        <span>/ {{ $units->lastPage() }}</span>
                    </div>

                    <a href="{{ $units->nextPageUrl() }}" class="btn-nav {{ $units->hasMorePages() ? '' : 'disabled' }}">Next</a>
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
                <p class="text-muted">You won't be able to revert this action! All related material data may be affected.</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-bold">Yes, Delete it!</button>
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
        
        // Initialize Select2 for modals
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('.select2-modal').each(function() {
                $(this).select2({
                    dropdownParent: $(this).closest('.modal-content'),
                    width: '100%'
                });
            });
        });
    });
</script>
@endpush
@endsection
