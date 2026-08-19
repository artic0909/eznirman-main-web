@extends(getRoutePrefix() == 'coordinator.' ? 'admin.layouts.app' : 'account.layouts.app')

@section('title', 'Account Codes')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Account Code Registry</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item" aria-current="page">Account Code</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Account Code Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Add New Account Code</h5>
            </div>
            <div class="card-body">
                <form action="{{ route(getRoutePrefix() . 'accountcode.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Account Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Sales Revenue, Cash Account, GST" required>
                        <small class="text-muted"><i class="ti ti-info-circle me-1"></i> Account Code will be generated automatically (e.g. 01, 02, 010...).</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Account Code</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Account Codes List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Registered Account Codes</h5>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route(getRoutePrefix() . 'accountcode.index') }}" method="GET" class="row mb-4">
                    <div class="col-md-9 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Code or Name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route(getRoutePrefix() . 'accountcode.index') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Code</th>
                                <th>Account Name</th>
                                <th>Created/Updated By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($accountCodes->currentPage() - 1) * $accountCodes->perPage() + 1;
                            @endphp
                            @forelse($accountCodes as $index => $aCode)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td><span class="badge bg-light-primary text-primary fw-bold text-uppercase">{{ $aCode->code }}</span></td>
                                <td><span class="text-dark fw-500">{{ $aCode->name }}</span></td>
                                <td>
                                    @if($aCode->updated_by)
                                        <small class="text-muted d-block">Updated: {{ $aCode->updated_by }}</small>
                                    @elseif($aCode->created_by)
                                        <small class="text-muted d-block">Created: {{ $aCode->created_by }}</small>
                                    @else
                                        <small class="text-muted d-block">-</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $aCode->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route(getRoutePrefix() . 'accountcode.destroy', $aCode->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $aCode->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Update Account Code</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route(getRoutePrefix() . 'accountcode.update', $aCode->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Account Code</label>
                                                            <input type="text" class="form-control bg-light text-uppercase" value="{{ $aCode->code }}" readonly disabled>
                                                            <small class="text-muted">Code is automatically assigned and cannot be modified.</small>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Account Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $aCode->name }}" required>
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
                                <td colspan="4" class="text-center text-muted py-5">No account codes found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($accountCodes->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $accountCodes->previousPageUrl() }}" class="btn-nav {{ $accountCodes->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    <div class="page-input-group">
                        <input type="number" value="{{ $accountCodes->currentPage() }}" min="1" max="{{ $accountCodes->lastPage() }}" id="goto-page">
                        <span>/ {{ $accountCodes->lastPage() }}</span>
                    </div>
                    <a href="{{ $accountCodes->nextPageUrl() }}" class="btn-nav {{ $accountCodes->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Global Delete Confirmation Modal -->
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
                <p class="text-muted">Deleting this account code will remove it permanently from the registry.</p>
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
    });
</script>
@endpush
@endsection
