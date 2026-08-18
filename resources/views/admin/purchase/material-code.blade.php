@extends('admin.layouts.app')

@section('title', 'Material Codes')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Material Code Registry</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Register</li>
                    <li class="breadcrumb-item" aria-current="page">Material Code</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Material Code Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Add New Material Code</h5>
            </div>
            <div class="card-body">
                <form action="{{ route(getRoutePrefix() . 'purchase.material-codes.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Material Category <span class="text-danger">*</span></label>
                        <select name="product_category_id" class="form-control select2" data-placeholder="Select Category" required>
                            <option value=""></option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Sub Category <span class="text-danger">*</span></label>
                        <input type="text" name="sub_category" class="form-control" placeholder="e.g. Electrical, Plumbing" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Sub Category Two <small>(Optional)</small></label>
                        <input type="text" name="sub_category_two" class="form-control" placeholder="e.g. Residential, Industrial">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Brand <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control" placeholder="e.g. Samsung, RFL" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Material Name (Item) <span class="text-danger">*</span></label>
                        <input type="text" name="material_name" class="form-control" placeholder="e.g. Copper Wire, PVC Pipe" required>
                        <small class="text-muted"><i class="ti ti-info-circle me-1"></i> Item Code will be generated automatically.</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Material Code</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Material Codes List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Registered Material Codes</h5>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route(getRoutePrefix() . 'purchase.material-codes.index') }}" method="GET" class="row mb-4">
                    <div class="col-md-5 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Code or Name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="category_id" class="form-control select2" data-placeholder="All Categories">
                            <option value=""></option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route(getRoutePrefix() . 'purchase.material-codes.index') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                        <button type="submit" name="export" value="excel" class="btn btn-success flex-grow-1"><i class="ti ti-table-export"></i></button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Code</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Brand</th>
                                <th>Material Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($materialCodes->currentPage() - 1) * $materialCodes->perPage() + 1;
                            @endphp
                            @forelse($materialCodes as $index => $mCode)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td><span class="badge bg-light-primary text-primary fw-bold text-uppercase">{{ $mCode->code }}</span></td>
                                <td><span class="text-dark fw-500">{{ $mCode->category->name }}</span></td>
                                <td>
                                    <div class="lh-1">
                                        <div class="fw-bold text-dark small">{{ $mCode->sub_category }}</div>
                                        @if($mCode->sub_category_two)
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $mCode->sub_category_two }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $mCode->brand }}</td>
                                <td>{{ $mCode->material_name }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $mCode->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route(getRoutePrefix() . 'purchase.material-codes.destroy', $mCode->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $mCode->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Update Material Code</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route(getRoutePrefix() . 'purchase.material-codes.update', $mCode->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Material Category</label>
                                                            <select name="product_category_id" class="form-control select2-modal" required>
                                                                @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ $mCode->product_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label">Sub Category</label>
                                                                    <input type="text" name="sub_category" class="form-control" value="{{ $mCode->sub_category }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label">Sub Category Two</label>
                                                                    <input type="text" name="sub_category_two" class="form-control" value="{{ $mCode->sub_category_two }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Brand</label>
                                                            <input type="text" name="brand" class="form-control" value="{{ $mCode->brand }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Material Name</label>
                                                            <input type="text" name="material_name" class="form-control" value="{{ $mCode->material_name }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Item Code</label>
                                                            <input type="text" name="code" class="form-control bg-light text-uppercase" value="{{ $mCode->code }}" required>
                                                            <small class="text-muted">Avoid changing the code unless absolutely necessary.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">No material codes found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($materialCodes->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $materialCodes->previousPageUrl() }}" class="btn-nav {{ $materialCodes->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    <div class="page-input-group">
                        <input type="number" value="{{ $materialCodes->currentPage() }}" min="1" max="{{ $materialCodes->lastPage() }}" id="goto-page">
                        <span>/ {{ $materialCodes->lastPage() }}</span>
                    </div>
                    <a href="{{ $materialCodes->nextPageUrl() }}" class="btn-nav {{ $materialCodes->hasMorePages() ? '' : 'disabled' }}">Next</a>
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
                <p class="text-muted">Deleting this code will affect all future purchase entries!</p>
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
