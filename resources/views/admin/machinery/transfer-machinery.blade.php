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
                        <small class="text-muted mt-1 d-block"><i class="ti ti-info-circle"></i> The system will automatically detect the current site of the selected machine.</small>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Destination Site <span class="text-danger">*</span></label>
                        <select name="to_site_id" class="form-control select2" data-placeholder="Select Target Site" required>
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->site_code }} - {{ $site->site_name }}</option>
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

    <!-- Transfer History Registry -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>Transfer History Registry</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route('admin.machinery.transfer-machinery') }}" method="GET" class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Machine or Site..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="machinery_id" class="form-control select2" data-placeholder="All Machines">
                            <option value=""></option>
                            @foreach($machineries as $machine)
                            <option value="{{ $machine->id }}" {{ request('machinery_id') == $machine->id ? 'selected' : '' }}>{{ $machine->machine_code }} - {{ $machine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="site_id" class="form-control select2" data-placeholder="All Target Sites">
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->site_code }} - {{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('admin.machinery.transfer-machinery') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Machine</th>
                                <th>Origin Site</th>
                                <th>Destination Site</th>
                                <th>Transfer Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($transfers->currentPage() - 1) * $transfers->perPage() + 1;
                            @endphp
                            @forelse($transfers as $index => $transfer)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
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
                                    <span class="text-muted"><i class="ti ti-map-pin"></i> {{ $transfer->fromSite->site_name }}</span>
                                    @else
                                    <span class="badge bg-light-info text-info">Initial Entry</span>
                                    @endif
                                </td>
                                <td><span class="fw-bold text-dark"><i class="ti ti-arrow-right text-success"></i> {{ $transfer->toSite->site_code }} - {{ $transfer->toSite->site_name }}</span></td>
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
                                <td colspan="7" class="text-center text-muted py-5">No transfer records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($transfers->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $transfers->previousPageUrl() }}" class="btn-nav {{ $transfers->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    
                    <div class="page-input-group">
                        <input type="number" value="{{ $transfers->currentPage() }}" min="1" max="{{ $transfers->lastPage() }}" id="goto-page">
                        <span>/ {{ $transfers->lastPage() }}</span>
                    </div>

                    <a href="{{ $transfers->nextPageUrl() }}" class="btn-nav {{ $transfers->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

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