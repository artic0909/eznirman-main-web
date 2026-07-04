@extends('admin.layouts.app')

@section('title', 'Working Sites')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Working Sites Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Site Management</li>
                    <li class="breadcrumb-item" aria-current="page">Working Sites</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Site Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Add New Project Site</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.machinery.working-sites.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Site Code <span class="text-danger">*</span></label>
                        <input type="text" name="site_code" class="form-control" placeholder="e.g. SITE-001" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Site Name <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control" placeholder="Enter site name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control" placeholder="Enter site location" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Register Site</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sites List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>Project Sites Fleet</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route('admin.machinery.working-sites') }}" method="GET" class="row mb-4">
                    <div class="col-md-9 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Site Name, Code or Location..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('admin.machinery.working-sites') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                        <button type="submit" name="export" value="excel" class="btn btn-success flex-grow-1"><i class="ti ti-table-export"></i></button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Code</th>
                                <th>Site Name</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($sites->currentPage() - 1) * $sites->perPage() + 1;
                            @endphp
                            @forelse($sites as $index => $site)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td><span class="badge bg-light-primary text-primary fw-bold">{{ $site->site_code }}</span></td>
                                <td>{{ $site->site_name }}</td>
                                <td><i class="ti ti-map-pin text-muted"></i> {{ $site->location }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $site->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-id="{{ $site->id }}" data-url="{{ route('admin.machinery.working-sites.delete', $site->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $site->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Update Project Site</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.machinery.working-sites.update', $site->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Site Code</label>
                                                            <input type="text" name="site_code" class="form-control" value="{{ $site->site_code }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Site Name</label>
                                                            <input type="text" name="site_name" class="form-control" value="{{ $site->site_name }}" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Location</label>
                                                            <input type="text" name="location" class="form-control" value="{{ $site->location }}" required>
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
                                <td colspan="5" class="text-center text-muted py-5">No project sites found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($sites->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $sites->previousPageUrl() }}" class="btn-nav {{ $sites->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    
                    <div class="page-input-group">
                        <input type="number" value="{{ $sites->currentPage() }}" min="1" max="{{ $sites->lastPage() }}" id="goto-page">
                        <span>/ {{ $sites->lastPage() }}</span>
                    </div>

                    <a href="{{ $sites->nextPageUrl() }}" class="btn-nav {{ $sites->hasMorePages() ? '' : 'disabled' }}">Next</a>
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
                <p class="text-muted">You won't be able to revert this action! All related data will be lost.</p>
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
    });
</script>
@endpush
@endsection
