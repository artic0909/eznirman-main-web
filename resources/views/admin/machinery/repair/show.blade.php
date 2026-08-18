@extends('admin.layouts.app')

@section('title', 'Repair Machinery Details')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Machinery Details</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Machinery & Tools</li>
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'machinery.repair') }}">Repairing Machinery</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $machinery->machine_code }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Machine Info Card -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5>Asset Information</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($machinery->image)
                    <img src="{{ asset('storage/' . $machinery->image) }}" alt="machine" class="img-fluid rounded shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                    @else
                    <div class="bg-light text-muted d-flex flex-column align-items-center justify-content-center rounded py-5">
                        <i class="ti ti-photo" style="font-size: 3rem;"></i>
                        <p class="mt-2 mb-0">No Image Available</p>
                    </div>
                    @endif
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Machine Name</span>
                        <span class="fw-bold">{{ $machinery->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Asset Code</span>
                        <span class="badge bg-light-primary text-primary fs-6">{{ $machinery->machine_code }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Category</span>
                        <span class="badge bg-light-secondary text-dark">{{ $machinery->category->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-bottom-0">
                        <span class="text-muted">Condition</span>
                        <span class="badge bg-light-warning text-warning">Repairing</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Transaction History Card -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Transfer History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>From Site</th>
                                <th>To Site</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($machinery->transfers as $transfer)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d M, Y') }}</td>
                                <td>{{ $transfer->fromSite->site_name ?? 'Central' }}</td>
                                <td><span class="fw-bold text-success">{{ $transfer->toSite->site_name }}</span></td>
                                <td><span class="badge bg-light-success text-success">{{ ucfirst($transfer->status) }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">No transfer records found.</td>
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
