@extends('admin.layouts.app')
@section('title', 'Material Wastage')

@section('content')
<div class="row">
    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Material Wastage</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wastage History -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Wastage History</h5>
            </div>
            <div class="card-body">
                <!-- Search Filter -->
                <form action="{{ route(getRoutePrefix() . 'purchase.material-wastage') }}" method="GET" class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Material ID or Name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="site_id" class="form-control select2" data-placeholder="All Sites">
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->site_code }} - {{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light">From</span>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light">To</span>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route(getRoutePrefix() . 'purchase.material-wastage') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Material</th>
                                <th>Wasted Qty</th>
                                <th>Origin Site</th>
                                <th>Updated By</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($wastages->currentPage() - 1) * $wastages->perPage() + 1;
                            @endphp
                            @forelse($wastages as $index => $consume)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($consume->consume_date)->format('d M, Y') }}</td>
                                <td>
                                    <h6 class="mb-0">Product: {{ $consume->purchase->product_name }}</h6>
                                    <small class="text-muted">Inv: {{ $consume->purchase->invoice_no }}</small>
                                    <br><small class="text-muted">Code: {{ $consume->purchase->materialCode->material_name }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">{{ $consume->used_quantity }}</span> 
                                    <small class="text-muted">{{ $consume->unit }}</small>
                                </td>
                                <td>
                                    <span class="text-muted"><i class="ti ti-map-pin"></i> {{ $consume->fromSite->site_code }} - {{ $consume->fromSite->site_name ?? 'N/A' }}</span>
                                </td>
                                <td>@include('admin.partials.tracked-by', ['model' => $consume])</td>
                                <td class="text-wrap" style="max-width: 150px;"><small>{{ $consume->note ?? 'N/A' }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No wastage records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $wastages->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
