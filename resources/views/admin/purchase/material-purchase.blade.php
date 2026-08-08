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
                            <option value="{{ $site->id }}">{{ $site->site_code }} - {{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Purchase Date <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Material Code <span class="text-danger">*</span></label>
                        <select name="material_code_id" id="material_code_select" class="form-control select2" data-placeholder="Select Code" required>
                            <option value=""></option>
                            @foreach($materialCodes as $mCode)
                            <option value="{{ $mCode->id }}" data-material-name="{{ $mCode->material_name }}">{{ $mCode->code }} - {{ $mCode->material_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="product_name" id="product_name_input" class="form-control bg-light" placeholder="Specific product name" readonly required>
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
                        <label class="form-label">GST (Flat Amount)</label>
                        <input type="number" step="0.01" name="gst_amount" id="p_gst" class="form-control calc-amount" value="0.00" placeholder="0.00">
                        <small class="text-muted">Example: 20 for ₹20 GST</small>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="p_total" class="form-control bg-light" placeholder="0.00" readonly required title="(Qty * Rate) + GST">
                        <small class="text-primary fw-bold">Formula: (Qty × Rate) + GST</small>
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
                            <option value="{{ $site->id }}" {{ request('site_id') == $site->id ? 'selected' : '' }}>{{ $site->site_code }} - {{ $site->site_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 mb-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                        <a href="{{ route('admin.purchase.material-purchases.index') }}" class="btn btn-light flex-grow-1 border">Clear</a>
                        <button type="submit" name="export" value="excel" class="btn btn-success flex-grow-1"><i class="ti ti-table-export"></i></button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Purchase ID</th>
                                <th>Material Details</th>
                                <th>Site</th>
                                <th>Vendor & Invoice</th>
                                <th>Qty/Unit</th>
                                <th>Created by</th>
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
                                    <h6 class="mb-0">
                                        <span class="badge bg-light-secondary text-muted" style="font-size: 0.7rem;">{{ $purchase->material_unique_id }}</span>
                                    </h6>
                                </td>
                                <td>
                                    <h6 class="mb-0">Material: {{ $purchase->product_name }}</h6>
                                    <div class="d-flex gap-2 align-items-center">
                                        <small class="text-primary fw-bold">Material Code: {{ $purchase->materialCode->code }}</small>
                                    </div>
                                </td>

                                <td><span class="badge bg-light-info text-info">{{ $purchase->site->site_code }} - {{ $purchase->site->site_name }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $purchase->party_name }}</div>
                                    <small class="text-muted">Inv: {{ $purchase->invoice_no }}</small>
                                </td>
                                <td>{{ $purchase->quantity }} <span class="text-muted">{{ $purchase->unit->name }}</span></td>
                                
                                <td>
                                    @if($purchase->user_id)
                                        <span class="fw-bold">{{ $purchase->user->name }}</span> <br> <span class="fw-bold">{{ $purchase->user->code }}</span>
                                    @else
                                        <span class="fw-bold">Admin</span>
                                    @endif
                                </td>
                                
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
                                        <button class="btn btn-sm btn-icon btn-light-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $purchase->id }}" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $purchase->id }}" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-icon btn-light-danger btn-delete" data-url="{{ route('admin.purchase.material-purchases.destroy', $purchase->id) }}" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>

                                    <!-- View Modal -->
                                    <div class="modal fade" id="viewModal{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title text-white">Purchase Bill Details</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4 bg-light">
                                                    <div class="card shadow-none border">
                                                        <div class="card-body">
                                                            <!-- Bill Header -->
                                                            <div class="row border-bottom pb-3 mb-3">
                                                                <div class="col-sm-6">
                                                                    <h3 class="text-primary mb-1">Purchase Invoice</h3>
                                                                    <p class="mb-0 text-muted"><strong>Inv No:</strong> {{ $purchase->invoice_no }}</p>
                                                                    <p class="mb-0 text-muted"><strong>Purchase ID:</strong> {{ $purchase->material_unique_id }}</p>
                                                                </div>
                                                                <div class="col-sm-6 text-sm-end">
                                                                    <p class="mb-0"><strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</p>
                                                                    <p class="mb-0"><strong>Site:</strong> <span class="badge bg-light-info text-info">{{ $purchase->site->site_code }} - {{ $purchase->site->site_name }}</span></p>
                                                                    <p class="mb-0"><strong>Created By:</strong> 
                                                                        @if($purchase->user_id)
                                                                            {{ $purchase->user->name }} ({{ $purchase->user->code }})
                                                                        @else
                                                                            Admin
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Vendor Info -->
                                                            <div class="row mb-4">
                                                                <div class="col-sm-12">
                                                                    <h6 class="text-uppercase text-muted mb-2">Vendor Information</h6>
                                                                    <h5 class="mb-0">{{ $purchase->party_name }}</h5>
                                                                </div>
                                                            </div>

                                                            <!-- Item Table -->
                                                            <div class="table-responsive mb-4">
                                                                <table class="table table-bordered mb-0">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th class="text-center" style="width: 50px;">#</th>
                                                                            <th>Product Details</th>
                                                                            <th class="text-end">Quantity</th>
                                                                            <th class="text-end">Rate</th>
                                                                            <th class="text-end">Amount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="text-center">1</td>
                                                                            <td>
                                                                                <h6 class="mb-0">{{ $purchase->product_name }}</h6>
                                                                                <small class="text-muted">Code: {{ $purchase->materialCode->code }}</small>
                                                                            </td>
                                                                            <td class="text-end">{{ $purchase->quantity }} {{ $purchase->unit->name }}</td>
                                                                            <td class="text-end">₹{{ number_format($purchase->rate, 2) }}</td>
                                                                            <td class="text-end">₹{{ number_format($purchase->quantity * $purchase->rate, 2) }}</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Totals Section -->
                                                            <div class="row">
                                                                <div class="col-sm-7">
                                                                    <h6 class="text-uppercase text-muted mb-2">Remarks/Notes</h6>
                                                                    <div class="p-3 bg-light border rounded" style="min-height: 80px;">
                                                                        {{ $purchase->note ?: 'No remarks provided.' }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-5">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-borderless text-end mb-0">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="fw-bold text-muted">Sub Total :</td>
                                                                                    <td>₹{{ number_format($purchase->quantity * $purchase->rate, 2) }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="fw-bold text-muted">GST Amount :</td>
                                                                                    <td>₹{{ number_format($purchase->gst_amount, 2) }}</td>
                                                                                </tr>
                                                                                <tr class="border-top">
                                                                                    <td class="fw-bold fs-5 pt-2">Grand Total :</td>
                                                                                    <td class="fw-bold fs-5 text-primary pt-2">₹{{ number_format($purchase->amount, 2) }}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    @if($purchase->invoice_file)
                                                    <a href="{{ asset('storage/' . $purchase->invoice_file) }}" target="_blank" class="btn btn-outline-primary">
                                                        <i class="ti ti-file-text me-1"></i> View Original Invoice
                                                    </a>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
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
                                                                <option value="{{ $site->id }}" {{ $purchase->working_site_id == $site->id ? 'selected' : '' }}>{{ $site->site_code }} - {{ $site->site_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Purchase Date</label>
                                                            <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Material Code</label>
                                                            <select name="material_code_id" class="form-control select2-modal material-code-edit-select" required>
                                                                @foreach($materialCodes as $mCode)
                                                                <option value="{{ $mCode->id }}" data-material-name="{{ $mCode->material_name }}" {{ $purchase->material_code_id == $mCode->id ? 'selected' : '' }}>{{ $mCode->code }}  {{ $mCode->material_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Product Name</label>
                                                            <input type="text" name="product_name" class="form-control product-name-edit-input bg-light" value="{{ $purchase->product_name }}" readonly required>
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
                                                            <label class="form-label">GST (Flat)</label>
                                                            <input type="number" step="0.01" name="gst_amount" class="form-control edit-calc-gst" value="{{ $purchase->gst_amount }}">
                                                        </div>
                                                        <div class="col-md-4 form-group mb-3">
                                                            <label class="form-label">Total Amount</label>
                                                            <input type="number" step="0.01" name="amount" class="form-control edit-calc-total bg-light" value="{{ $purchase->amount }}" readonly required title="(Qty * Rate) + GST">
                                                            <small class="text-primary fw-bold">Formula: (Qty × Rate) + GST</small>
                                                        </div>
                                                        <div class="col-md-12 form-group mb-3">
                                                            <label class="form-label">Update Invoice File</label>
                                                            <input type="file" name="invoice_file" class="form-control" accept="image/*,.pdf">
                                                        </div>
                                                        <div class="col-md-12 form-group mb-3">
                                                            <label class="form-label">Note/Remarks</label>
                                                            <input type="text" name="note" class="form-control" placeholder="Any additional info" value="{{ $purchase->note }}">
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
        
        // Autofill Logic for Add Form
        $('#material_code_select').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const materialName = selectedOption.data('material-name');
            document.getElementById('product_name_input').value = materialName || '';
        });

        // Calculation & Autofill Logic for Edit Modals (Individual logic per modal)
        document.querySelectorAll('.modal').forEach(modal => {
            const eqty = modal.querySelector('.edit-calc-qty');
            const erate = modal.querySelector('.edit-calc-rate');
            const egst = modal.querySelector('.edit-calc-gst');
            const etotal = modal.querySelector('.edit-calc-total');
            const eMaterialSelect = modal.querySelector('.material-code-edit-select');
            const eProductNameInput = modal.querySelector('.product-name-edit-input');

            // Autofill for Edit Modal
            if(eMaterialSelect && eProductNameInput) {
                $(eMaterialSelect).on('change', function() {
                    const materialName = $(this).find(':selected').data('material-name');
                    eProductNameInput.value = materialName || '';
                });
            }

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
