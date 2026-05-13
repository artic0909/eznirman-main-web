@extends('admin.layouts.app')

@section('title', 'Material Purchases')

@section('content')
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Material Purchase Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Register</li>
                    <li class="breadcrumb-item" aria-current="page">Material Purchase</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Purchase Form -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Record New Material Purchase</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#addFormCollapse">
                    <i class="ti ti-plus"></i> Toggle Form
                </button>
            </div>
            <div class="card-body collapse {{ $errors->any() ? 'show' : '' }}" id="addFormCollapse">
                <form action="{{ route('admin.purchase.material-purchases.store') }}" method="POST" enctype="multipart/form-data" class="row">
                    @csrf
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Select Site <span class="text-danger">*</span></label>
                        <select name="working_site_id" class="form-control select2" required>
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Material Code <span class="text-danger">*</span></label>
                        <select name="material_code_id" class="form-control select2" data-placeholder="Select Code" required>
                            <option value=""></option>
                            @foreach($materialCodes as $mCode)
                            <option value="{{ $mCode->id }}">{{ $mCode->code }} - {{ $mCode->material_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="product_name" class="form-control" placeholder="Specific product name" required>
                    </div>

                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Party/Vendor Name <span class="text-danger">*</span></label>
                        <input type="text" name="party_name" class="form-control" placeholder="Enter vendor name" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Invoice No. <span class="text-danger">*</span></label>
                        <input type="text" name="invoice_no" class="form-control" placeholder="Enter invoice number" required>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="quantity" id="p_quantity" class="form-control calc-amount" placeholder="0.00" required>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label class="form-label">Unit <span class="text-danger">*</span></label>
                        <select name="unit_id" class="form-control select2" required>
                            <option value=""></option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label class="form-label">Rate (per unit) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="rate" id="p_rate" class="form-control calc-amount" placeholder="0.00" required>
                    </div>

                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">GST Amount</label>
                        <input type="number" step="0.01" name="gst_amount" id="p_gst" class="form-control calc-amount" value="0.00">
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="p_total" class="form-control" placeholder="0.00" readonly required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Invoice File (PDF/Image)</label>
                        <input type="file" name="invoice_file" class="form-control" accept="image/*,.pdf">
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Note/Remarks</label>
                        <input type="text" name="note" class="form-control" placeholder="Any additional info">
                    </div>

                    <div class="col-md-12 text-end mt-2">
                        <button type="submit" class="btn btn-success px-5 fw-bold">Record Purchase</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Purchases List -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Purchase Registry</h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form action="{{ route('admin.purchase.material-purchases.index') }}" method="GET" class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Product, Party or Invoice..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="site_id" class="form-control select2" data-placeholder="All Sites">
                            <option value=""></option>
                            @foreach($sites as $site)
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('admin.purchase.material-purchases.index') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Product Details</th>
                                <th>Site</th>
                                <th>Vendor & Invoice</th>
                                <th>Qty/Unit</th>
                                <th>Total Amount</th>
                                <th>File</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $startSl = ($purchases->currentPage() - 1) * $purchases->perPage() + 1;
                            @endphp
                            @forelse($purchases as $index => $purchase)
                            <tr>
                                <td>{{ $startSl + $index }}</td>
                                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</td>
                                <td>
                                    <h6 class="mb-0">{{ $purchase->product_name }}</h6>
                                    <small class="text-primary fw-bold">{{ $purchase->materialCode->code }}</small>
                                </td>
                                <td><span class="badge bg-light-info text-info">{{ $purchase->site->site_name }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $purchase->party_name }}</div>
                                    <small class="text-muted">Inv: {{ $purchase->invoice_no }}</small>
                                </td>
                                <td>{{ $purchase->quantity }} <span class="text-muted">{{ $purchase->unit->name }}</span></td>
                                <td><span class="fw-bold">₹{{ number_format($purchase->amount, 2) }}</span></td>
                                <td>
                                    @if($purchase->invoice_file)
                                    <a href="{{ asset('storage/' . $purchase->invoice_file) }}" target="_blank" class="btn btn-sm btn-icon btn-light-secondary">
                                        <i class="ti ti-file-text"></i>
                                    </a>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $purchase->id }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route('admin.purchase.material-purchases.destroy', $purchase->id) }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Update Purchase Record</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.purchase.material-purchases.update', $purchase->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body row">
                                                        <!-- Fields similar to add form -->
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Site</label>
                                                            <select name="working_site_id" class="form-control select2-modal" required>
                                                                @foreach($sites as $site)
                                                                <option value="{{ $site->id }}" {{ $purchase->working_site_id == $site->id ? 'selected' : '' }}>{{ $site->site_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Purchase Date</label>
                                                            <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Material Code</label>
                                                            <select name="material_code_id" class="form-control select2-modal" required>
                                                                @foreach($materialCodes as $mCode)
                                                                <option value="{{ $mCode->id }}" {{ $purchase->material_code_id == $mCode->id ? 'selected' : '' }}>{{ $mCode->code }} - {{ $mCode->material_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Product Name</label>
                                                            <input type="text" name="product_name" class="form-control" value="{{ $purchase->product_name }}" required>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Vendor Name</label>
                                                            <input type="text" name="party_name" class="form-control" value="{{ $purchase->party_name }}" required>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Invoice No.</label>
                                                            <input type="text" name="invoice_no" class="form-control" value="{{ $purchase->invoice_no }}" required>
                                                        </div>
                                                        <div class="col-md-2 form-group mb-3">
                                                            <label class="form-label">Quantity</label>
                                                            <input type="number" step="0.01" name="quantity" class="form-control edit-calc-qty" value="{{ $purchase->quantity }}" required>
                                                        </div>
                                                        <div class="col-md-2 form-group mb-3">
                                                            <label class="form-label">Unit</label>
                                                            <select name="unit_id" class="form-control select2-modal" required>
                                                                @foreach($units as $unit)
                                                                <option value="{{ $unit->id }}" {{ $purchase->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 form-group mb-3">
                                                            <label class="form-label">Rate</label>
                                                            <input type="number" step="0.01" name="rate" class="form-control edit-calc-rate" value="{{ $purchase->rate }}" required>
                                                        </div>
                                                        <div class="col-md-2 form-group mb-3">
                                                            <label class="form-label">GST</label>
                                                            <input type="number" step="0.01" name="gst_amount" class="form-control edit-calc-gst" value="{{ $purchase->gst_amount }}">
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Total Amount</label>
                                                            <input type="number" step="0.01" name="amount" class="form-control edit-calc-total" value="{{ $purchase->amount }}" readonly required>
                                                        </div>
                                                        <div class="col-md-12 form-group mb-3">
                                                            <label class="form-label">Update Invoice File</label>
                                                            <input type="file" name="invoice_file" class="form-control" accept="image/*,.pdf">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Purchase</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">No purchase records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($purchases->hasPages())
                <div class="custom-pagination">
                    <a href="{{ $purchases->previousPageUrl() }}" class="btn-nav {{ $purchases->onFirstPage() ? 'disabled' : '' }}">Prev</a>
                    <div class="page-input-group">
                        <input type="number" value="{{ $purchases->currentPage() }}" min="1" max="{{ $purchases->lastPage() }}" id="goto-page">
                        <span>/ {{ $purchases->lastPage() }}</span>
                    </div>
                    <a href="{{ $purchases->nextPageUrl() }}" class="btn-nav {{ $purchases->hasMorePages() ? '' : 'disabled' }}">Next</a>
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
                <p class="text-muted">Deleting this purchase will permanently remove the record and invoice!</p>
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
        // Calculation Logic for Add Form
        const calcInputs = document.querySelectorAll('.calc-amount');
        const qtyInp = document.getElementById('p_quantity');
        const rateInp = document.getElementById('p_rate');
        const gstInp = document.getElementById('p_gst');
        const totalInp = document.getElementById('p_total');

        const calculateTotal = () => {
            const qty = parseFloat(qtyInp.value) || 0;
            const rate = parseFloat(rateInp.value) || 0;
            const gst = parseFloat(gstInp.value) || 0;
            totalInp.value = ((qty * rate) + gst).toFixed(2);
        };

        calcInputs.forEach(input => input.addEventListener('input', calculateTotal));

        // Calculation Logic for Edit Modals (Individual logic per modal)
        document.querySelectorAll('.modal').forEach(modal => {
            const eqty = modal.querySelector('.edit-calc-qty');
            const erate = modal.querySelector('.edit-calc-rate');
            const egst = modal.querySelector('.edit-calc-gst');
            const etotal = modal.querySelector('.edit-calc-total');

            if(eqty && erate && etotal) {
                [eqty, erate, egst].forEach(inp => {
                    if(inp) {
                        inp.addEventListener('input', () => {
                            const q = parseFloat(eqty.value) || 0;
                            const r = parseFloat(erate.value) || 0;
                            const g = parseFloat(egst ? egst.value : 0) || 0;
                            etotal.value = ((q * r) + g).toFixed(2);
                        });
                    }
                });
            }
        });

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
        
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('.select2-modal').each(function() {
                $(this).select2({
                    dropdownParent: $(this).closest('.modal-content'),
                    width: '100%'
                });
            });
        });
    });
</script>
@endpush
@endsection
