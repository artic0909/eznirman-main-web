@extends('admin.layouts.app')

@section('title', 'Transfer Machinery')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Machinery Transfer Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Machinery & Tools</li>
                    <li class="breadcrumb-item" aria-current="page">Transfer Machinery</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Transfer Form -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Initiate Asset Transfer</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.machinery.transfer.store') }}" method="POST" class="row">
                    @csrf
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Select Machinery <span class="text-danger">*</span></label>
                        <select name="machinery_id" class="form-control select2" data-placeholder="Choose Machine" required>
                            <option value=""></option>
                            @foreach($machineries as $machine)
                            <option value="{{ $machine->id }}">{{ $machine->machine_code }} - {{ $machine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Destination Site <span class="text-danger">*</span></label>
                        <select name="to_site_id" class="form-control select2" data-placeholder="Select Target Site" required>
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Transfer Date <span class="text-danger">*</span></label>
                        <input type="date" name="transfer_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                        <label class="form-label">Transfer Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="Reasons for transfer, condition check, etc."></textarea>
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-warning px-4 fw-bold">Execute Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Transfer History -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Transfer History Registry</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Machine</th>
                                <th>Origin</th>
                                <th>Destination</th>
                                <th>Transfer Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfers as $transfer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="mb-0">{{ $transfer->machinery->name }}</h6>
                                            <small class="text-primary fw-bold">{{ $transfer->machinery->machine_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($transfer->fromSite)
                                    <span class="text-muted">{{ $transfer->fromSite->site_name }}</span>
                                    @else
                                    <span class="badge bg-light-info text-info">Initial Entry</span>
                                    @endif
                                </td>
                                <td><span class="fw-bold text-dark">{{ $transfer->toSite->site_name }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d M, Y') }}</td>
                                <td>
                                    @if($transfer->status == 'completed')
                                    <span class="badge bg-light-success text-success">Completed</span>
                                    @elseif($transfer->status == 'pending')
                                    <span class="badge bg-light-warning text-warning">Pending</span>
                                    @else
                                    <span class="badge bg-light-danger text-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $transfer->remarks ?? 'N/A' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No transfer records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection