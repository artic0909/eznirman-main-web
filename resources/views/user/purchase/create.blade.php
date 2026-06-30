@extends('user.layouts.app')

@section('content')
<style>
  .purchase-container {
    padding: 20px;
    color: var(--text);
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
  }
  .form-card {
    background: var(--surface);
    border: 1px solid var(--surface-border);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  }
  .form-header {
    border-bottom: 1px solid var(--surface-border);
    padding-bottom: 16px;
    margin-bottom: 24px;
  }
  .form-title {
    font-family: var(--font-outfit);
    font-size: 24px;
    color: var(--white);
    font-weight: 600;
  }
  
  /* Radio toggles */
  .type-toggle {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 28px;
  }
  .radio-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    background: var(--surface-light);
    padding: 14px 24px;
    border-radius: 12px;
    border: 1px solid var(--surface-border);
    transition: all 0.2s ease;
    font-family: var(--font-outfit);
    font-size: 15px;
    color: var(--text-muted);
    flex: 1;
    justify-content: center;
    min-width: 200px;
  }
  .radio-label:has(input:checked) {
    background: var(--primary-glow);
    border-color: var(--primary);
    color: var(--white);
    box-shadow: 0 0 15px rgba(124, 111, 247, 0.2);
  }
  .radio-label input[type="radio"] {
    accent-color: var(--primary);
    width: 18px;
    height: 18px;
    cursor: pointer;
  }

  /* Form Grid */
  .form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
  }
  @media (min-width: 768px) {
    .form-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (min-width: 1024px) {
    .form-grid { grid-template-columns: repeat(3, 1fr); }
  }
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .form-group.full-width {
    grid-column: 1 / -1;
  }
  .form-label {
    font-family: var(--font-mono);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
  }
  .form-control {
    background: var(--surface-light);
    border: 1px solid var(--surface-border);
    border-radius: 10px;
    padding: 14px 16px;
    color: var(--white);
    font-family: var(--font-outfit);
    font-size: 14px;
    outline: none;
    transition: all 0.2s;
    width: 100%;
  }
  .form-control:focus {
    border-color: var(--primary);
    background: var(--surface);
  }
  .form-control[readonly] {
    background: rgba(255,255,255,0.02);
    color: var(--text-muted);
    border-color: transparent;
  }
  
  /* Select2 overriding in user theme */
  .select2-container--default .select2-selection--single {
    height: 50px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 48px !important;
  }

  .btn-submit {
    background: var(--primary);
    color: var(--white);
    border: none;
    padding: 16px 28px;
    border-radius: 12px;
    font-family: var(--font-outfit);
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
    margin-top: 32px;
    box-shadow: 0 4px 15px rgba(124, 111, 247, 0.3);
  }
  .btn-submit:hover { 
    background: #685be3; 
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(124, 111, 247, 0.4);
  }
  .btn-submit:active { 
    transform: translateY(0); 
  }
  
  .text-danger { color: var(--danger); }
</style>

<div class="purchase-container">
  <div class="form-card">
    <div class="form-header">
      <h2 class="form-title">Record Material Purchase</h2>
      <p class="text-muted" style="font-size: 14px; margin-top: 6px;">Submit a new purchase record for your site.</p>
    </div>

    <!-- Toggle Section -->
    <div class="type-toggle">
      <label class="radio-label">
        <input type="radio" name="purchase_type" value="authorized" id="type_authorized" checked onchange="toggleFormFields()">
        <span>Authorized Purchase</span>
      </label>
      <label class="radio-label">
        <input type="radio" name="purchase_type" value="unauthorized" id="type_unauthorized" onchange="toggleFormFields()">
        <span>Unauthorized Purchase</span>
      </label>
    </div>

    <!-- Form Action can be customized by the backend route -->
    <form action="#" method="POST" enctype="multipart/form-data" id="purchaseForm">
      @csrf
      
      <div class="form-grid">
        
        <!-- Site Name-Code (Shared) -->
        <div class="form-group" id="group_working_site_id">
          <label class="form-label">Site Name-Code <span class="text-danger">*</span></label>
          <select name="working_site_id" id="working_site_id" class="form-control select2" required>
            <option value=""></option>
            @if(isset($sites))
              @foreach($sites as $site)
                <option value="{{ $site->id }}" selected>{{ $site->site_code }} - {{ $site->site_name }}</option>
              @endforeach
            @else
               <!-- Fallback/Mockup -->
               <option value="1" selected>SIT001 - Default Working Site</option>
            @endif
          </select>
        </div>

        <!-- Date (Shared) -->
        <div class="form-group" id="group_purchase_date">
          <label class="form-label">Purchase Date <span class="text-danger">*</span></label>
          <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- Material Code (Authorized Only) -->
        <div class="form-group auth-only">
          <label class="form-label">Material Code <span class="text-danger">*</span></label>
          <select name="material_code_id" id="material_code_select" class="form-control select2" data-placeholder="Select Code">
            <option value=""></option>
            @if(isset($materialCodes))
              @foreach($materialCodes as $mCode)
              <option value="{{ $mCode->id }}" data-material-name="{{ $mCode->material_name }}">{{ $mCode->code }} - {{ $mCode->material_name }}</option>
              @endforeach
            @endif
          </select>
        </div>

        <!-- Product Name (Shared) -->
        <div class="form-group" id="group_product_name">
          <label class="form-label">Product Name <span class="text-danger">*</span></label>
          <input type="text" name="product_name" id="product_name_input" class="form-control" placeholder="Specific product name" required>
        </div>

        <!-- Party Name (Authorized Only) -->
        <div class="form-group auth-only">
          <label class="form-label">Party/Vendor Name <span class="text-danger">*</span></label>
          <input type="text" name="party_name" id="party_name" class="form-control" placeholder="Enter vendor name">
        </div>

        <!-- Invoice No (Authorized Only) -->
        <div class="form-group auth-only">
          <label class="form-label">Invoice No. <span class="text-danger">*</span></label>
          <input type="text" name="invoice_no" id="invoice_no" class="form-control" placeholder="Enter invoice number">
        </div>

        <!-- Quantity (Authorized Only) -->
        <div class="form-group auth-only">
          <label class="form-label">Quantity <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="quantity" id="p_quantity" class="form-control calc-amount" placeholder="0.00">
        </div>

        <!-- Unit (Authorized Only) -->
        <div class="form-group auth-only">
          <label class="form-label">Unit <span class="text-danger">*</span></label>
          <select name="unit_id" id="unit_id" class="form-control select2">
            <option value=""></option>
            @if(isset($units))
              @foreach($units as $unit)
              <option value="{{ $unit->id }}">{{ $unit->name }}</option>
              @endforeach
            @endif
          </select>
        </div>

        <!-- Rate (Authorized Only) -->
        <div class="form-group auth-only">
          <label class="form-label">Rate (per unit) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="rate" id="p_rate" class="form-control calc-amount" placeholder="0.00">
        </div>

        <!-- GST (Authorized Only) -->
        <div class="form-group auth-only">
          <label class="form-label">GST (Flat Amount)</label>
          <input type="number" step="0.01" name="gst_amount" id="p_gst" class="form-control calc-amount" value="0.00" placeholder="0.00">
          <small class="text-muted" style="font-size:10px; margin-top:4px;">Example: 20 for ₹20 GST</small>
        </div>

        <!-- Amount (Shared) -->
        <div class="form-group" id="group_amount">
          <label class="form-label">Total Amount <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="amount" id="p_total" class="form-control" placeholder="0.00" required>
          <small class="text-primary auth-msg" style="font-size:10px; margin-top:4px; color:var(--primary);">Formula: (Qty × Rate) + GST</small>
        </div>

        <!-- Invoice File (Shared) -->
        <div class="form-group" id="group_invoice_file">
          <label class="form-label">Invoice File (PDF/Image)</label>
          <input type="file" name="invoice_file" class="form-control" accept="image/*,.pdf">
        </div>

        <!-- Remarks (Shared) -->
        <div class="form-group full-width" id="group_note">
          <label class="form-label">Note/Remarks</label>
          <input type="text" name="note" class="form-control" placeholder="Any additional info">
        </div>

      </div>

      <button type="submit" class="btn-submit">Submit Purchase Record</button>
    </form>
  </div>
</div>

<script>
  function toggleFormFields() {
    const isAuthorized = document.getElementById('type_authorized').checked;
    
    const authElements = document.querySelectorAll('.auth-only');
    const authMsgs = document.querySelectorAll('.auth-msg');
    
    const totalInput = document.getElementById('p_total');
    const productInput = document.getElementById('product_name_input');
    
    if (isAuthorized) {
      // Show authorized fields
      authElements.forEach(el => {
        el.style.display = 'flex';
        const inputs = el.querySelectorAll('input, select');
        inputs.forEach(inp => {
          if (inp.id !== 'p_gst') inp.setAttribute('required', 'required'); 
        });
      });
      authMsgs.forEach(el => el.style.display = 'block');
      
      // Total amount becomes readonly and calculated
      totalInput.setAttribute('readonly', 'readonly');
      
      // Product name becomes readonly (populated by Material Code select)
      productInput.setAttribute('readonly', 'readonly');
      
    } else {
      // Hide authorized fields
      authElements.forEach(el => {
        el.style.display = 'none';
        const inputs = el.querySelectorAll('input, select');
        inputs.forEach(inp => {
          inp.removeAttribute('required');
        });
      });
      authMsgs.forEach(el => el.style.display = 'none');
      
      // Total amount becomes editable
      totalInput.removeAttribute('readonly');
      
      // Product name becomes editable
      productInput.removeAttribute('readonly');
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Calculation Logic for Authorized Mode
    document.querySelectorAll('.calc-amount').forEach(item => {
      item.addEventListener('input', function() {
        if (document.getElementById('type_authorized').checked) {
          const qty = parseFloat(document.getElementById('p_quantity').value) || 0;
          const rate = parseFloat(document.getElementById('p_rate').value) || 0;
          const gst = parseFloat(document.getElementById('p_gst').value) || 0;
          const total = (qty * rate) + gst;
          document.getElementById('p_total').value = total.toFixed(2);
        }
      });
    });

    // Material Code Auto-fill
    if (typeof $ !== 'undefined') {
      $('#material_code_select').on('change', function() {
        if (document.getElementById('type_authorized').checked) {
          const selectedOption = $(this).find('option:selected');
          const materialName = selectedOption.data('material-name');
          $('#product_name_input').val(materialName || '');
        }
      });

      // Init Select2 if loaded
      if ($.fn.select2) {
        $('.select2').select2({
          width: '100%'
        });
      }
    }

    // Initial check
    toggleFormFields();
  });
</script>
@endsection
