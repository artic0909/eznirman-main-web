@extends('admin.layouts.app')

@section('title', 'Machine List')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Site-wise Machinery Fleet</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Machinery & Tools</li>
                    <li class="breadcrumb-item" aria-current="page">Machine List</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Fleet KPI Overview Cards -->
<div class="row mb-3">
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card h-100 mb-0 shadow-none border">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold d-block mb-1">Total Fleet</span>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalMachinesCount }}</h4>
                    </div>
                    <div class="avatar avatar-md bg-light-primary rounded d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="ti ti-tools fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card h-100 mb-0 shadow-none border">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold d-block mb-1">On Active Sites</span>
                        <h4 class="mb-0 fw-bold text-success">{{ $totalTransferredCount }}</h4>
                    </div>
                    <div class="avatar avatar-md bg-light-success rounded d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="ti ti-building fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card h-100 mb-0 shadow-none border">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold d-block mb-1">Unassigned / Depot</span>
                        <h4 class="mb-0 fw-bold text-warning">{{ $unassignedCount }}</h4>
                    </div>
                    <div class="avatar avatar-md bg-light-warning rounded d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="ti ti-box fs-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-3">
        <div class="card h-100 mb-0 shadow-none border">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold d-block mb-1">Total Sites</span>
                        <h4 class="mb-0 fw-bold text-secondary">{{ count($sites) }}</h4>
                    </div>
                    <div class="avatar avatar-md bg-light-secondary rounded d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="ti ti-map-pin fs-4 text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($selectedSite)
<!-- Highlighted Filtered Site Summary Banner -->
<div class="alert alert-primary d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 py-3" role="alert">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary text-uppercase px-2 py-1 fs-6 fw-bold">
            {{ $selectedSite->site_code }}
        </span>
        <h5 class="mb-0 fw-bold text-dark">{{ $selectedSite->site_name }}</h5>
        @if(!empty($selectedSite->location))
        <span class="text-muted ms-2"><i class="ti ti-map-pin"></i> {{ $selectedSite->location }}</span>
        @endif
    </div>
    <div>
        <span class="badge bg-light-primary text-primary fs-6 px-3 py-2 fw-bold">
            Total {{ $selectedSiteMachineCount }} {{ Str::plural('Machine', $selectedSiteMachineCount) }} Stationed Here
        </span>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>Site-wise Machinery List</h5>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <a href="{{ route(getRoutePrefix() . 'machinery.transfer-machinery') }}" class="btn btn-warning btn-sm">
                            <i class="ti ti-arrows-exchange"></i> Transfer Machinery
                        </a>
                        <a href="{{ route(getRoutePrefix() . 'machinery.add-machinery') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus"></i> Add Machinery
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route(getRoutePrefix() . 'machinery.machine-list') }}" method="GET" class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <select name="site_id" class="form-control select2" data-placeholder="Search site code or name...">
                            <option value=""></option>
                            <option value="unassigned" {{ request('site_id') === 'unassigned' ? 'selected' : '' }}>
                                Initial / Unassigned ({{ $unassignedCount }} Machines)
                            </option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>
                                {{ $site->site_code }} - {{ $site->site_name }} ({{ $site->machines_count }} Machines)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Code, Name, Site..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2 mb-2">
                        <select name="category_id" class="form-control select2" data-placeholder="All Categories">
                            <option value=""></option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <select name="condition" class="form-control select2" data-placeholder="All Conditions">
                            <option value=""></option>
                            <option value="running" {{ request('condition') == 'running' ? 'selected' : '' }}>Running</option>
                            <option value="repair" {{ request('condition') == 'repair' ? 'selected' : '' }}>Repairing</option>
                            <option value="damage" {{ request('condition') == 'damage' ? 'selected' : '' }}>Damaged</option>
                            <option value="missing" {{ request('condition') == 'missing' ? 'selected' : '' }}>Missing</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>

                    <div class="col-md-12 mb-2 d-flex justify-content-end gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route(getRoutePrefix() . 'machinery.machine-list') }}" class="btn btn-light border">Clear</a>
                        <button type="submit" name="export" value="excel" class="btn btn-success" title="Export to Excel"><i class="ti ti-table-export"></i></button>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40px;">SL</th>
                                <th>Machine Information</th>
                                <th>Category</th>
                                <th>Current Site (Location)</th>
                                <th>Latest Movement / Transfer</th>
                                <th>Condition</th>
                                <th>Registration Date</th>
                                <th class="text-center" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($machineries->currentPage() - 1) * $machineries->perPage() + 1;
                            @endphp
                            @forelse($machineries as $index => $machine)
                            @php
                                $latest = $machine->latestTransfer;
                                $currentSite = $latest ? $latest->toSite : null;
                                $fromSite = $latest ? $latest->fromSite : null;
                            @endphp
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($machine->image)
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#imageModal{{ $machine->id }}">
                                            <img src="{{ asset('storage/' . $machine->image) }}" alt="machine" class="rounded me-2" width="45" height="45" style="object-fit: cover;">
                                        </a>
                                        @else
                                        <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded me-2" style="width: 45px; height: 45px;">
                                            <i class="ti ti-tools"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $machine->name }}</h6>
                                            <span class="badge bg-light-primary text-primary fw-bold font-monospace mt-1">
                                                {{ $machine->machine_code }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light-secondary text-dark">
                                        {{ $machine->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($currentSite)
                                    <div>
                                        <span class="badge bg-primary text-uppercase fw-bold mb-1">
                                            {{ $currentSite->site_code }}
                                        </span>
                                        <div class="fw-bold text-dark">{{ $currentSite->site_name }}</div>
                                        @if($currentSite->location)
                                        <small class="text-muted d-block">
                                            <i class="ti ti-map-pin"></i> {{ $currentSite->location }}
                                        </small>
                                        @endif
                                    </div>
                                    @else
                                    <div>
                                        <span class="badge bg-secondary fw-bold mb-1">
                                            UNASSIGNED
                                        </span>
                                        <div class="fw-bold text-muted">Initial Depot / Not Transferred</div>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($latest)
                                    <div>
                                        <div class="fw-bold text-dark">
                                            <i class="ti ti-calendar-event text-primary me-1"></i>
                                            {{ \Carbon\Carbon::parse($latest->transfer_date)->format('M d, Y') }}
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            &rarr; From: <span class="fw-semibold">{{ $fromSite ? $fromSite->site_name : 'Initial Depot' }}</span>
                                        </small>
                                    </div>
                                    @else
                                    <div>
                                        <div class="fw-bold text-muted">
                                            <i class="ti ti-calendar text-muted me-1"></i>
                                            {{ \Carbon\Carbon::parse($machine->entry_date)->format('M d, Y') }}
                                        </div>
                                        <small class="text-muted d-block mt-1">Initial Entry (No transfers)</small>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    @if($machine->condition == 'running')
                                    <span class="badge bg-light-success text-success">Running</span>
                                    @elseif($machine->condition == 'repair')
                                    <span class="badge bg-light-warning text-warning">Repairing</span>
                                    @elseif($machine->condition == 'damage')
                                    <span class="badge bg-light-danger text-danger">Damaged</span>
                                    @elseif($machine->condition == 'missing')
                                    <span class="badge bg-light-danger text-danger">Missing</span>
                                    @else
                                    <span class="badge bg-light-secondary text-secondary">{{ ucfirst($machine->condition) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">
                                        {{ \Carbon\Carbon::parse($machine->entry_date)->format('d M, Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $machine->id }}" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">No machine records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($machineries->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $machineries->previousPageUrl() }}" class="btn-nav {{ $machineries->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    
                    <div class="page-input-group">
                        <input type="number" value="{{ $machineries->currentPage() }}" min="1" max="{{ $machineries->lastPage() }}" id="goto-page">
                        <span>/ {{ $machineries->lastPage() }}</span>
                    </div>

                    <a href="{{ $machineries->nextPageUrl() }}" class="btn-nav {{ $machineries->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- All Modals (Rendered Outside Table) -->
@foreach($machineries as $machine)
@php
    $latest = $machine->latestTransfer;
    $currentSite = $latest ? $latest->toSite : null;
@endphp
<!-- Details Modal for Machine -->
<div class="modal fade" id="detailsModal{{ $machine->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $machine->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="detailsModalLabel{{ $machine->id }}">
                    <i class="ti ti-tools me-2"></i> Machine Specifications & Location History
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4 text-center">
                        @if($machine->image)
                        <img src="{{ asset('storage/' . $machine->image) }}" alt="machine" class="img-fluid rounded shadow-sm border mb-2" style="max-height: 180px; width: 100%; object-fit: cover;">
                        @else
                        <div class="bg-light text-muted d-flex flex-column align-items-center justify-content-center rounded py-5 border">
                            <i class="ti ti-photo fs-1"></i>
                            <p class="mt-2 mb-0 small">No Image Uploaded</p>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="text-muted small d-block">Machine Name</label>
                                <span class="fw-bold text-dark">{{ $machine->name }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small d-block">Asset Code</label>
                                <span class="badge bg-primary fs-6">{{ $machine->machine_code }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small d-block">Category</label>
                                <span class="fw-semibold">{{ $machine->category->name ?? 'N/A' }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small d-block">Condition Status</label>
                                <div>
                                    @if($machine->condition == 'running')
                                    <span class="badge bg-success">Running</span>
                                    @elseif($machine->condition == 'repair')
                                    <span class="badge bg-warning">Under Repair</span>
                                    @elseif($machine->condition == 'damage')
                                    <span class="badge bg-danger">Damaged</span>
                                    @elseif($machine->condition == 'missing')
                                    <span class="badge bg-dark">Missing</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small d-block">Initial Registration Date</label>
                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($machine->entry_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small d-block">Total Movement Records</label>
                                <span class="badge bg-light-primary text-primary fw-bold">{{ $machine->transfers->count() }} Transfers</span>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="p-3 bg-light rounded border">
                            <label class="text-muted small d-block">Current Site Assignment (Latest Record):</label>
                            @if($currentSite)
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span class="badge bg-primary fw-bold">{{ $currentSite->site_code }}</span>
                                <span class="fw-bold text-dark fs-6">{{ $currentSite->site_name }}</span>
                            </div>
                            @if($currentSite->location)
                            <small class="text-muted d-block mt-1"><i class="ti ti-map-pin text-danger"></i> {{ $currentSite->location }}</small>
                            @endif
                            <small class="text-primary fw-bold d-block mt-1">
                                <i class="ti ti-calendar me-1"></i> Transferred On: {{ \Carbon\Carbon::parse($latest->transfer_date)->format('M d, Y') }}
                            </small>
                            @else
                            <span class="badge bg-secondary mt-1">Unassigned / Initial Depot</span>
                            @endif
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-2"><i class="ti ti-history me-1 text-primary"></i> Complete Movement Journey</h6>
                <div class="table-responsive border rounded">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Origin Site</th>
                                <th>Destination Site</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($machine->transfers->sortByDesc('id') as $tIdx => $transfer)
                            <tr>
                                <td>{{ $tIdx + 1 }}</td>
                                <td class="fw-bold">{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('M d, Y') }}</td>
                                <td>{{ $transfer->fromSite->site_name ?? 'Initial Depot' }}</td>
                                <td class="fw-bold text-primary">{{ $transfer->toSite->site_name ?? 'N/A' }} ({{ $transfer->toSite->site_code ?? '' }})</td>
                                <td><span class="badge bg-light-success text-success">{{ ucfirst($transfer->status) }}</span></td>
                                <td class="small text-muted">{{ $transfer->remarks ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">No movement history recorded yet. Machine is currently at initial registration depot.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route(getRoutePrefix() . 'machinery.transfer-machinery') }}?machinery_id={{ $machine->id }}" class="btn btn-warning fw-bold btn-sm">
                    <i class="ti ti-arrows-exchange"></i> Transfer this Asset
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if($machine->image)
<!-- Image Lightbox Modal -->
<div class="modal fade" id="imageModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div class="modal-body text-center p-0">
                <img src="{{ asset('storage/' . $machine->image) }}" alt="machine" class="img-fluid rounded shadow-lg">
                <div class="mt-2">
                    <button type="button" class="btn btn-light btn-sm fw-bold" data-bs-dismiss="modal">Close Preview</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
