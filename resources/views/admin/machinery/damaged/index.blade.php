@extends('admin.layouts.app')

@section('title', 'Damaged Machinery')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Damaged Machinery</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route(getRoutePrefix() . 'dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Machinery & Tools</li>
                    <li class="breadcrumb-item" aria-current="page">Damaged Machinery</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Machinery List with Filters -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>All Damaged Machinery Assets</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route(getRoutePrefix() . 'machinery.damaged') }}" method="GET" class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Code or Name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="category_id" class="form-control select2" data-placeholder="All Categories">
                            <option value=""></option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route(getRoutePrefix() . 'machinery.damaged') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                        <button type="submit" name="export" value="excel" class="btn btn-success flex-grow-1"><i class="ti ti-table-export"></i></button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>SL.</th>
                                <th>Image</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Condition</th>
                                <th>Entry Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($machineries->currentPage() - 1) * $machineries->perPage() + 1;
                            @endphp
                            @forelse($machineries as $index => $machine)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td>
                                    @if($machine->image)
                                    <img src="{{ asset('storage/' . $machine->image) }}" alt="machine" class="rounded" width="45" height="45" style="object-fit: cover;">
                                    @else
                                    <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 45px; height: 45px;">
                                        <i class="ti ti-photo"></i>
                                    </div>
                                    @endif
                                </td>
                                <td><span class="fw-bold text-primary">{{ $machine->machine_code }}</span></td>
                                <td>{{ $machine->name }}</td>
                                <td><span class="badge bg-light-secondary text-dark">{{ $machine->category->name }}</span></td>
                                <td>
                                    <span class="badge bg-light-danger text-danger">Damaged</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($machine->entry_date)->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route(getRoutePrefix() . 'machinery.damaged.show', $machine->id) }}" class="btn btn-sm btn-info fw-bold">
                                        <i class="ti ti-eye me-1"></i> View Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <div class="mb-2">
                                        <i class="ti ti-tool-off" style="font-size: 3rem;"></i>
                                    </div>
                                    No damaged machinery assets found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                @if($machineries->hasPages())
                <div class="custom-pagination mt-4">
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
