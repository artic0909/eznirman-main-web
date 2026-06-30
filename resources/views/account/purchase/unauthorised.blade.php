@extends('account.layouts.app')

@section('title', 'Unauthorized Purchases')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Unauthorized Purchase Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('account.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Register</li>
                    <li class="breadcrumb-item" aria-current="page">Unauthorized Purchase</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Purchases List -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5>Unauthorized Purchase Registry</h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('account.purchase.unauthorized-purchases.index') }}" method="GET" class="row mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="roleFilter" class="form-select select2">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">User</label>
                            <select name="user_id" id="userFilter" class="form-select select2" data-selected="{{ request('user_id') }}">
                                <option value="">All Users</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                            <a href="{{ route('account.purchase.unauthorized-purchases.index') }}" class="btn btn-light border px-2 d-flex align-items-center justify-content-center" title="Clear Filters">
                                <i class="ti ti-x"></i>
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Purchase ID</th>
                                <th>Product Details</th>
                                <th>Site</th>
                                <th>Created by</th>
                                <th>Total Amount</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($purchases->currentPage() - 1) * $purchases->perPage() + 1;
                            @endphp
                            @forelse($purchases as $index => $purchase)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</td>
                                <td>
                                    <h6 class="mb-0">
                                        <span class="badge bg-light-danger text-danger" style="font-size: 0.7rem;">{{ $purchase->unauthorized_unique_id }}</span>
                                    </h6>
                                </td>
                                <td>
                                    <h6 class="mb-0">{{ $purchase->product_name }}</h6>
                                </td>

                                <td><span class="badge bg-light-info text-info">{{ $purchase->site->site_code ?? 'N/A' }} - {{ $purchase->site->site_name ?? 'N/A' }}</span></td>
                                
                                <td>
                                    @if($purchase->user_id)
                                        <span class="fw-bold">{{ $purchase->user->name }}</span> <br> <span class="text-muted" style="font-size: 0.8rem;">{{ $purchase->user->code }}</span>
                                    @else
                                        <span class="fw-bold text-muted">Unknown</span>
                                    @endif
                                </td>
                                
                                <td><span class="fw-bold text-dark">₹{{ number_format($purchase->amount, 2) }}</span></td>
                                
                                <td>
                                    @if($purchase->invoice_file)
                                    <a href="{{ asset('storage/' . $purchase->invoice_file) }}" target="_blank" class="btn btn-sm btn-icon btn-light-secondary">
                                        <i class="ti ti-file-text"></i>
                                    </a>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route('account.purchase.unauthorized-purchases.destroy', $purchase->id) }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="ti ti-receipt text-muted mb-2" style="font-size: 2rem;"></i><br>
                                    No unauthorized purchase records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($purchases->hasPages())
                <div class="custom-pagination mt-3">
                    <a href="{{ $purchases->previousPageUrl() }}" class="btn-nav {{ $purchases->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    <div class="page-input-group d-inline-flex align-items-center gap-2 px-3">
                        <input type="number" value="{{ $purchases->currentPage() }}" min="1" max="{{ $purchases->lastPage() }}" id="goto-page" class="form-control form-control-sm" style="width: 60px; text-align: center;">
                        <span class="text-muted">/ {{ $purchases->lastPage() }}</span>
                    </div>
                    <a href="{{ $purchases->nextPageUrl() }}" class="btn-nav {{ $purchases->hasMorePages() ? '' : 'disabled' }}">Next</a>
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
                <p class="text-muted">Deleting this unauthorized purchase will permanently remove the record and invoice!</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-bold">Delete Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Global Delete
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const deleteForm = document.getElementById('deleteForm');

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                deleteForm.setAttribute('action', url);
                deleteModal.show();
            });
        });

        // Pagination
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

        // Role/User Filter Logic
        const roleFilter = $('#roleFilter');
        const userFilter = $('#userFilter');
        const selectedUserId = userFilter.data('selected');

        function loadUsers(role, preselectId = null) {
            userFilter.empty().append('<option value="">All Users</option>');
            if (!role) {
                userFilter.trigger('change');
                return;
            }

            $.ajax({
                url: '{{ route('account.cashmanagement.users_by_role') }}',
                type: 'GET',
                data: { role: role },
                success: function(users) {
                    users.forEach(function(user) {
                        const isSelected = preselectId && preselectId == user.id ? 'selected' : '';
                        userFilter.append(`<option value="${user.id}" ${isSelected}>${user.name} (${user.code || 'N/A'})</option>`);
                    });
                    userFilter.trigger('change');
                }
            });
        }

        // On load, if role is selected, load users
        if (roleFilter.val()) {
            loadUsers(roleFilter.val(), selectedUserId);
        }

        // On role change
        roleFilter.on('change', function() {
            loadUsers($(this).val());
        });
    });
</script>
@endpush
@endsection
