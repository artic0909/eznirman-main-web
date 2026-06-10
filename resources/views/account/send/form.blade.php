@extends('account.layouts.app')

@section('title', 'Make Payments')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Make Payment to User</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('account.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Petty Cash</li>
                    <li class="breadcrumb-item"><a href="{{ route('account.cashmanagement.index') }}">Cash Management</a></li>
                    <li class="breadcrumb-item" aria-current="page">Make Payments</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">Send Money</h5>
            </div>
            <div class="card-body p-4">



                <form action="{{ route('account.cashmanagement.process') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Role <span class="text-danger">*</span></label>
                        <select id="roleSelect" class="form-select select2" required>
                            <option value="">-- Choose Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Select User <span class="text-danger">*</span></label>
                        <select name="user_id" id="userSelect" class="form-select select2" required disabled>
                            <option value="">-- Choose User --</option>
                        </select>
                        @error('user_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 bg-light p-3 rounded d-none" id="balanceContainer">
                        <span class="text-muted d-block mb-1">Current Wallet Balance:</span>
                        <h3 class="mb-0 text-primary">₹<span id="currentBalance">0.00</span></h3>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Amount to Send (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control form-control-lg" placeholder="e.g. 1212.50" step="0.01" min="0.01" required>
                        @error('amount')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                        <i class="ti ti-send me-2"></i> Send Money
                    </button>
                </form>
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
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        const roleSelect = $('#roleSelect');
        const userSelect = $('#userSelect');

        // Fetch users based on role
        roleSelect.on('change', function() {
            var role = $(this).val();
            userSelect.empty().append('<option value="">-- Choose User --</option>');
            $('#balanceContainer').addClass('d-none');
            
            if (role) {
                userSelect.prop('disabled', false);
                $.ajax({
                    url: '{{ route('account.cashmanagement.users_by_role') }}',
                    type: 'GET',
                    data: { role: role },
                    success: function(users) {
                        users.forEach(function(user) {
                            var balance = user.wallet ? user.wallet.current_balance : 0;
                            userSelect.append(`<option value="${user.id}" data-balance="${balance}">${user.name} (${user.code || 'N/A'})</option>`);
                        });
                        userSelect.trigger('change');
                    }
                });
            } else {
                userSelect.prop('disabled', true);
            }
        });

        // Listen for user change to show balance
        userSelect.on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var balance = selectedOption.data('balance');
            
            if ($(this).val()) {
                $('#balanceContainer').removeClass('d-none');
                
                // Format the balance properly
                var formattedBalance = parseFloat(balance).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                $('#currentBalance').text(formattedBalance);
            } else {
                $('#balanceContainer').addClass('d-none');
            }
        });
    });
</script>
@endpush
@endsection
