@extends('admin.layouts.app')

@section('title', 'Manage Machinery')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add & Manage Machinery</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Machinery & Tools</li>
                    <li class="breadcrumb-item" aria-current="page">Add Machinery</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Machinery Form -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Add New Machinery Asset</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addFormCollapse">
                    <i class="ti ti-plus"></i> Toggle Form
                </button>
            </div>
            <div class="card-body collapse" id="addFormCollapse">
                <form action="{{ route('admin.machinery.machinery.store') }}" method="POST" enctype="multipart/form-data" class="row">
                    @csrf
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="machine_category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Machine Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Caterpillar Excavator" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Machine Code <span class="text-danger">*</span></label>
                        <input type="text" name="machine_code" class="form-control" placeholder="e.g. EX-2024-001" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Entry Date <span class="text-danger">*</span></label>
                        <input type="date" name="entry_date" class="form-control" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Initial Condition <span class="text-danger">*</span></label>
                        <select name="condition" class="form-control" required>
                            <option value="running">Running</option>
                            <option value="repair">Under Repair</option>
                            <option value="damage">Damaged</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Machine Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-success px-4 fw-bold">Register Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Machinery List -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Registered Machinery Fleet</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Condition</th>
                                <th>Entry Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($machineries as $machine)
                            <tr>
                                <td>
                                    @if($machine->image)
                                    <img src="{{ asset('storage/' . $machine->image) }}" alt="machine" class="rounded" width="50" height="50" style="object-fit: cover;">
                                    @else
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                        <i class="ti ti-photo"></i>
                                    </div>
                                    @endif
                                </td>
                                <td><span class="fw-bold text-primary">{{ $machine->machine_code }}</span></td>
                                <td>{{ $machine->name }}</td>
                                <td><span class="badge bg-light-secondary text-dark">{{ $machine->category->name }}</span></td>
                                <td>
                                    @if($machine->condition == 'running')
                                    <span class="badge bg-light-success text-success">Running</span>
                                    @elseif($machine->condition == 'repair')
                                    <span class="badge bg-light-warning text-warning">Repairing</span>
                                    @else
                                    <span class="badge bg-light-danger text-danger">Damaged</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($machine->entry_date)->format('d M, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $machine->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route('admin.machinery.machinery.delete', $machine->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Update Machinery Asset</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.machinery.machinery.update', $machine->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body row">
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label class="form-label">Category</label>
                                                            <select name="machine_category_id" class="form-control" required>
                                                                @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ $machine->machine_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label class="form-label">Machine Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $machine->name }}" required>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label class="form-label">Machine Code</label>
                                                            <input type="text" name="machine_code" class="form-control" value="{{ $machine->machine_code }}" required>
                                                        </div>
                                                        <div class="col-md-6 form-group mb-3">
                                                            <label class="form-label">Condition</label>
                                                            <select name="condition" class="form-control" required>
                                                                <option value="running" {{ $machine->condition == 'running' ? 'selected' : '' }}>Running</option>
                                                                <option value="repair" {{ $machine->condition == 'repair' ? 'selected' : '' }}>Repairing</option>
                                                                <option value="damage" {{ $machine->condition == 'damage' ? 'selected' : '' }}>Damaged</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 form-group mb-3">
                                                            <label class="form-label">Update Image</label>
                                                            <input type="file" name="image" class="form-control" accept="image/*">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Asset</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No machinery assets registered.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
                <p class="text-muted">Deleting this asset will remove it from all site transfer histories!</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-bold">Delete Asset</button>
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
    });
</script>
@endpush
@endsection