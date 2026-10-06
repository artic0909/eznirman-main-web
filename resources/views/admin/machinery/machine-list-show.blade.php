@extends('admin.layouts.app')

@section('title', 'Machinery Asset Details')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Machinery Details & Location Record</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Machinery & Tools</li>
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'machinery.machine-list') }}">Machine List</a></li>
                    <li class="breadcrumb-item" aria-current="page">{{ $machinery->machine_code }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Machine Info Card -->
    <div class="col-lg-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold"><i class="ti ti-info-circle me-1 text-primary"></i> Asset Profile</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    @if($machinery->image)
                    <img src="{{ asset('storage/' . $machinery->image) }}" alt="machine" class="img-fluid rounded shadow-sm border" style="max-height: 220px; width: 100%; object-fit: cover;">
                    @else
                    <div class="bg-light text-muted d-flex flex-column align-items-center justify-content-center rounded py-5 border">
                        <i class="ti ti-photo" style="font-size: 3.5rem;"></i>
                        <p class="mt-2 mb-0">No Image Uploaded</p>
                    </div>
                    @endif
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Machine Name</span>
                        <span class="fw-bold text-dark">{{ $machinery->name }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Asset Code</span>
                        <span class="badge bg-primary fs-6">{{ $machinery->machine_code }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Category</span>
                        <span class="badge bg-light-secondary text-dark fw-bold">{{ $machinery->category->name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Condition</span>
                        @if($machinery->condition == 'running')
                        <span class="badge bg-success">Running</span>
                        @elseif($machinery->condition == 'repair')
                        <span class="badge bg-warning">Under Repair</span>
                        @elseif($machinery->condition == 'damage')
                        <span class="badge bg-danger">Damaged</span>
                        @elseif($machinery->condition == 'missing')
                        <span class="badge bg-dark">Missing</span>
                        @endif
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted">Registration Date</span>
                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($machinery->entry_date)->format('M d, Y') }}</span>
                    </li>
                </ul>

                @php
                    $latest = $machinery->latestTransfer;
                    $currentSite = $latest ? $latest->toSite : null;
                @endphp

                <div class="p-3 bg-light rounded border mt-3">
                    <span class="text-muted small d-block">Current Site Assignment:</span>
                    @if($currentSite)
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <span class="badge bg-primary fw-bold">{{ $currentSite->site_code }}</span>
                        <span class="fw-bold text-dark fs-6">{{ $currentSite->site_name }}</span>
                    </div>
                    @if($currentSite->location)
                    <small class="text-muted d-block mt-1"><i class="ti ti-map-pin text-danger"></i> {{ $currentSite->location }}</small>
                    @endif
                    <small class="text-primary fw-bold d-block mt-1">
                        <i class="ti ti-calendar me-1"></i> Transfer Date: {{ \Carbon\Carbon::parse($latest->transfer_date)->format('M d, Y') }}
                    </small>
                    @else
                    <span class="badge bg-secondary mt-1">Unassigned / Initial Depot</span>
                    @endif
                </div>

                <div class="mt-4 d-grid gap-2">
                    <a href="{{ route(getRoutePrefix() . 'machinery.transfer-machinery') }}?machinery_id={{ $machinery->id }}" class="btn btn-warning fw-bold">
                        <i class="ti ti-arrows-exchange"></i> Transfer Machine
                    </a>
                    <a href="{{ route(getRoutePrefix() . 'machinery.machine-list') }}" class="btn btn-light border">
                        <i class="ti ti-arrow-left"></i> Back to Machine List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Movement History Card -->
    <div class="col-lg-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="ti ti-history me-1 text-primary"></i> Site Transfer & Movement History</h5>
                <span class="badge bg-primary">{{ $machinery->transfers->count() }} Transfers Recorded</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Transfer Date</th>
                                <th>Origin Site</th>
                                <th>Destination Site</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($machinery->transfers->sortByDesc('id') as $index => $transfer)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('M d, Y') }}</td>
                                <td>
                                    @if($transfer->fromSite)
                                    <span><i class="ti ti-building me-1"></i> {{ $transfer->fromSite->site_name }} ({{ $transfer->fromSite->site_code }})</span>
                                    @else
                                    <span class="text-muted">Initial Entry / Central Yard</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light-primary text-primary fw-bold fs-6">
                                        <i class="ti ti-building me-1"></i> {{ $transfer->toSite->site_name }} ({{ $transfer->toSite->site_code }})
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light-success text-success fw-bold">{{ ucfirst($transfer->status) }}</span>
                                </td>
                                <td class="small text-muted">{{ $transfer->remarks ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ti ti-arrows-exchange-2 fs-1 text-muted d-block mb-2"></i>
                                    No transfer records found for this machine yet. It remains at the initial warehouse/depot.
                                </td>
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
