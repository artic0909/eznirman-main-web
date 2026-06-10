@extends('account.layouts.app')

@section('title', 'Make Payments')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Make Payment to Supervisor</h5>
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

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('account.cashmanagement.process') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Supervisor <span class="text-danger">*</span></label>
                        <select name="supervisor_id" id="supervisorSelect" class="form-select select2" required>
                            <option value="">-- Choose Supervisor --</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}" 
                                        data-balance="{{ $supervisor->wallet ? $supervisor->wallet->current_balance : 0 }}">
                                    {{ $supervisor->name }} ({{ $supervisor->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('supervisor_id')
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
<!-- Include Select2 CSS & JS if not already in app.blade.php -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Choose Supervisor --",
            allowClear: true
        });

        // Listen for change
        $('#supervisorSelect').on('change', function() {
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
