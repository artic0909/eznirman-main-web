@extends('account.layouts.app')

@section('title', 'Cash Management')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Cash Management Transactions</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('account.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Petty Cash</li>
                    <li class="breadcrumb-item" aria-current="page">Cash Management</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-light-success border-success border-start border-4 mb-3 mb-md-0">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-success mb-0 fw-bold">Total Payments Transfer</p>
                        <h4 class="mb-0 text-success">₹{{ number_format($totalCredits, 2) }}</h4>
                    </div>
                    <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="ti ti-arrow-down fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-light-danger border-danger border-start border-4 mb-0">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-danger mb-0 fw-bold">Total Debits from User</p>
                        <h4 class="mb-0 text-danger">₹{{ number_format($totalDebits, 2) }}</h4>
                    </div>
                    <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="ti ti-arrow-up fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('account.cashmanagement.index') }}" method="GET" id="filterForm">
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
                            <a href="{{ route('account.cashmanagement.index') }}" class="btn btn-light border px-2 d-flex align-items-center justify-content-center" title="Clear Filters">
                                <i class="ti ti-x"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>All Transactions</h5>
                <div class="d-flex gap-2">
                    <button type="submit" name="export" value="1" form="filterForm" class="btn btn-success btn-sm">
                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                    </button>
                    <a href="{{ route('account.cashmanagement.send') }}" class="btn btn-primary btn-sm">Make Payment</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User / Role</th>
                                <th>Description</th>
                                <th>Pay To</th>
                                <th>Account Code</th>
                                <th>Credit/ Debit</th>
                                <th>After Balance (₹)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                            <tr>
                                <td>
                                    <span class="d-block fw-bold">{{ $tx->date ? $tx->date->format('d M Y') : $tx->created_at->format('d M Y') }}</span>
                                    <span class="text-muted small">{{ $tx->created_at->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <span class="fw-500 text-dark d-block">{{ $tx->wallet && $tx->wallet->user ? $tx->wallet->user->name : 'N/A' }}</span>
                                    <span class="text-muted small">{{ $tx->wallet && $tx->wallet->user ? ucfirst($tx->wallet->user->role) : '' }}</span>
                                </td>
                                <td>{{ $tx->note }}</td>
                                <td>
                                    @if($tx->pay_to)
                                        <span class="fw-500">{{ $tx->pay_to }}</span>
                                        @if($tx->pay_to_code) <span class="text-muted small">({{ $tx->pay_to_code }})</span> @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tx->accountcode)
                                        <span class="badge bg-light-primary text-primary">{{ $tx->accountcode->name }} ({{ $tx->accountcode->code }})</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tx->type === 'credit')
                                        <span class="fw-bold text-success">₹{{ number_format($tx->amount, 2) }}</span>

                                    @elseif($tx->type === 'debit')
                                        <span class="fw-bold text-danger">₹{{ number_format($tx->amount, 2) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-500">₹{{ number_format($tx->balance_after, 2) }}</span>
                                </td>
                                <td>
                                    
                                        <form action="{{ route('account.cashmanagement.refund', $tx->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Delete & Refund">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">No transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($transactions->hasPages())
                <div class="custom-pagination mt-4">
                    <a href="{{ $transactions->previousPageUrl() }}" class="btn-nav {{ $transactions->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    <div class="page-input-group">
                        <input type="number" value="{{ $transactions->currentPage() }}" min="1" max="{{ $transactions->lastPage() }}" id="goto-page">
                        <span>/ {{ $transactions->lastPage() }}</span>
                    </div>
                    <a href="{{ $transactions->nextPageUrl() }}" class="btn-nav {{ $transactions->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Include Select2 CSS & JS and Bootstrap 5 Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Init Select2
        if($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }

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

        // Handle Role -> User dynamic dropdown
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

        // SweetAlert2 for Delete Confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this transaction? The wallet balance will be adjusted accordingly.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@endsection
