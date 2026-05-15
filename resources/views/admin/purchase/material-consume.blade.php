@extends('admin.layouts.app')

@section('title', 'Material Consume/Transfer')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Stock Consumption & Transfers</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Register</li>
                    <li class="breadcrumb-item" aria-current="page">Material Consume</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Consume Form -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Log Material Usage or Internal Transfer</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.purchase.material-consumes.store') }}" method="POST" class="row">
                    @csrf
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Material Purchase Ref. <span class="text-danger">*</span></label>
                        <select name="material_purchase_id" id="purchase_ref" class="form-control select2" required>
                            <option value=""></option>
                            @foreach($purchases as $purchase)
                            <option value="{{ $purchase->id }}" 
                                    data-qty="{{ $purchase->quantity }}" 
                                    data-unit="{{ $purchase->unit->name }}"
                                    data-site-id="{{ $purchase->working_site_id }}">Code: {{ $purchase->materialCode->code }} | Product: {{ $purchase->product_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="consume_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="use_now" id="use_type" class="form-control select2" required>
                            <option value="0">Consume at Site</option>
                            <option value="1">Site to Site Transfer</option>
                        </select>
                    </div>

                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Quantity to Use/Move <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="used_quantity" class="form-control" placeholder="0.00" required>
                    </div>
                    
                    <div class="col-md-3 form-group mb-3" id="from_site_div">
                        <label class="form-label">Origin Site</label>
                        <input type="hidden" name="from_site_id" id="from_site_hidden">
                        <select name="from_site_id_display" id="from_site" class="form-control select2" required>
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->site_code }} - {{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group mb-3 d-none" id="to_site_div">
                        <label class="form-label">Destination Site <span class="text-danger">*</span></label>
                        <select name="to_site_id" class="form-control select2">
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->site_code }} - {{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Note/Remarks</label>
                        <input type="text" name="note" class="form-control" placeholder="Purpose of usage">
                    </div>

                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary px-5 fw-bold">Record Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Consumption History -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Stock Movement History</h5>
            </div>
            <div class="card-body">
                <!-- Search Filter -->
                <form action="{{ route('admin.purchase.material-consumes.index') }}" method="GET" class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Material ID or Material Name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="site_id" class="form-control select2" data-placeholder="All Sites">
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->site_code }} - {{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="type" class="form-control select2" data-placeholder="All Types">
                            <option value=""></option>
                            <option value="0" {{ request('type') === '0' ? 'selected' : '' }}>Consumed</option>
                            <option value="1" {{ request('type') === '1' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light">From</span>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <span class="input-group-text bg-light">To</span>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Apply Filters</button>
                        <a href="{{ route('admin.purchase.material-consumes.index') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Material</th>
                                <th>Type</th>
                                <th>Used Qty</th>
                                <th>Avl Qty</th>
                                <th>Location Flow</th>
                                <th>Remarks</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($consumes->currentPage() - 1) * $consumes->perPage() + 1;
                            @endphp
                            @forelse($consumes as $index => $consume)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($consume->consume_date)->format('d M, Y') }}</td>
                                <td>
                                    <h6 class="mb-0">Product: {{ $consume->purchase->product_name }}</h6>
                                    <small class="text-muted">Inv: {{ $consume->purchase->invoice_no }}</small>
                                    <br><small class="text-muted">Code: {{ $consume->purchase->materialCode->material_name }}</small>
                                    <br><small class="text-muted">Qty: {{ $consume->purchase->quantity }} {{ $consume->purchase->unit->name }}</small>
                                </td>
                                <td>
                                    @if($consume->use_now == 0)
                                    <span class="badge bg-light-info text-info">Consumed</span>
                                    @else
                                    <span class="badge bg-light-warning text-warning">Transfer</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $consume->used_quantity }}</span> 
                                    <small class="text-muted">{{ $consume->unit }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $consume->available_quantity }}</span> 
                                    <small class="text-muted">{{ $consume->unit }}</small>
                                </td>
                                <td>
                                    @if($consume->use_now == 0)
                                    <span class="text-muted"><i class="ti ti-map-pin"></i> {{ $consume->fromSite->site_code }} - {{ $consume->fromSite->site_name ?? 'N/A' }}</span>
                                    @else
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted">{{ $consume->fromSite->site_code }} - {{ $consume->fromSite->site_name ?? 'N/A' }}</small>
                                        <i class="ti ti-arrow-right mx-2 text-success"></i>
                                        <span class="fw-bold">{{ $consume->toSite->site_code }} - {{ $consume->toSite->site_name ?? 'N/A' }}</span>
                                    </div>
                                    @endif
                                </td>
                                <td><small>{{ $consume->note ?? 'N/A' }}</small></td>
                                <!-- <td>
                                    <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route('admin.purchase.material-consumes.destroy', $consume->id) }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td> -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">No movement records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($consumes->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $consumes->previousPageUrl() }}" class="btn-nav {{ $consumes->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    <div class="page-input-group">
                        <input type="number" value="{{ $consumes->currentPage() }}" min="1" max="{{ $consumes->lastPage() }}" id="goto-page">
                        <span>/ {{ $consumes->lastPage() }}</span>
                    </div>
                    <a href="{{ $consumes->nextPageUrl() }}" class="btn-nav {{ $consumes->hasMorePages() ? '' : 'disabled' }}">Next</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Global Delete Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <i class="ti ti-alert-triangle text-danger" style="font-size: 3.5rem;"></i>
                </div>
                <h4 class="mb-2">Are you sure?</h4>
                <p class="text-muted">Deleting this record will restore the available quantity for the original purchase!</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-bold">Delete Record</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const purchaseRef = $('#purchase_ref');
        const fromSite = $('#from_site');
        const fromSiteHidden = $('#from_site_hidden');
        const useType = $('#use_type');
        const toSiteDiv = document.getElementById('to_site_div');

        const syncHiddenSite = () => {
            fromSiteHidden.val(fromSite.val());
        };

        purchaseRef.on('change', function() {
            const purchaseId = $(this).val();
            if (!purchaseId) return;

            // Fetch sites that have stock for this purchase
            $.ajax({
                url: `/admin/purchase/material-consumes/get-stock-locations/${purchaseId}`,
                type: 'GET',
                success: function(locations) {
                    fromSite.empty().append('<option value=""></option>');
                    
                    locations.forEach(loc => {
                        fromSite.append(`<option value="${loc.id}" data-balance="${loc.balance}">
                            ${loc.code} - ${loc.name} (Available: ${loc.balance})
                        </option>`);
                    });

                    // If only one site has stock, auto-select it
                    if (locations.length === 1) {
                        fromSite.val(locations[0].id).trigger('change');
                    } else if (locations.length > 1) {
                        // If multiple sites, let user pick
                        fromSite.prop('disabled', false).trigger('change');
                    }
                    syncHiddenSite();
                }
            });
        });

        fromSite.on('change', syncHiddenSite);

        useType.on('change', function() {
            const isTransfer = $(this).val() == '1';
            
            if (isTransfer) {
                toSiteDiv.classList.remove('d-none');
                toSiteDiv.querySelector('select').required = true;
                // For transfer, user might want to pick origin manually if multiple exist
            } else {
                toSiteDiv.classList.add('d-none');
                toSiteDiv.querySelector('select').required = false;
            }
            syncHiddenSite();
        });

        // Initialize state
        if ($('#use_type').val() == '0') {
            $('#from_site').prop('disabled', true).trigger('change');
        }

        // Global Delete
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const deleteForm = document.getElementById('deleteForm');

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                deleteForm.setAttribute('action', url);
                deleteModal.show();
            });
        });

        // Pagination
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
