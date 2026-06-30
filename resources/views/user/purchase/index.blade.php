@extends('user.layouts.app')

@section('content')
<style>
  .purchase-container {
    color: var(--text);
    width: 100%;
    grid-column: 1 / -1;
  }
  
  .header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
  }

  .page-title {
    font-family: var(--font-outfit);
    font-size: 28px;
    color: var(--white);
    font-weight: 600;
  }

  .btn-primary {
    background: var(--primary);
    color: var(--white);
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-family: var(--font-outfit);
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    box-shadow: 0 4px 15px rgba(124, 111, 247, 0.3);
  }
  .btn-primary:hover { 
    background: #685be3;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(124, 111, 247, 0.4);
    color: var(--white);
  }

  /* Filters */
  .filter-card {
    background: var(--surface);
    border: 1px solid var(--surface-border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
  }
  .filter-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
  }
  @media (min-width: 768px) {
    .filter-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (min-width: 1024px) {
    .filter-grid { grid-template-columns: repeat(3, 1fr); }
  }
  .form-control {
    background: var(--surface-light);
    border: 1px solid var(--surface-border);
    border-radius: 8px;
    padding: 10px 14px;
    color: var(--white);
    font-family: var(--font-outfit);
    font-size: 14px;
    width: 100%;
    outline: none;
  }
  .form-control:focus { border-color: var(--primary); }

  /* Table styling */
  .table-responsive {
    overflow-x: auto;
    background: var(--surface);
    border-radius: 16px;
    border: 1px solid var(--surface-border);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  }
  .table {
    width: 100%;
    border-collapse: collapse;
    color: var(--text);
    font-family: var(--font-outfit);
  }
  .table th, .table td {
    padding: 16px 20px;
    text-align: left;
    border-bottom: 1px solid var(--surface-border);
    white-space: nowrap;
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--surface-border);
    white-space: nowrap;
    background: rgba(255,255,255,0.02);
  }
  td {
    padding: 16px;
    font-size: 14px;
    color: var(--text);
    border-bottom: 1px solid var(--surface-border);
    vertical-align: middle;
    white-space: nowrap;
  }
  tr:last-child td {
    border-bottom: none;
  }
  tr:hover { background: rgba(255,255,255,0.02); }

  .badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    font-family: var(--font-mono);
    letter-spacing: 0.5px;
  }
  .badge-info { background: rgba(124, 111, 247, 0.15); color: var(--primary); border: 1px solid var(--primary); }
  .badge-success { background: rgba(16, 185, 129, 0.15); color: var(--success); border: 1px solid var(--success); }
  
  .btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--surface-light);
    border: 1px solid var(--surface-border);
    color: var(--text-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.2s;
    text-decoration: none;
  }
  .btn-icon:hover {
    color: var(--white);
    background: var(--primary);
    border-color: var(--primary);
  }
  .btn-icon.danger:hover {
    background: var(--danger);
    border-color: var(--danger);
  }

  /* Mobile Table to Cards */
  @media (max-width: 768px) {
    .table { min-width: 100%; }
    .table thead { display: none; }
    .table, .table tbody, .table tr, .table td { display: block; width: 100%; }
    .table tr { 
      margin-bottom: 16px; 
      border: 1px solid var(--surface-border); 
      border-radius: 12px; 
      padding: 12px; 
      background: var(--surface-light); 
    }
    .table tr:last-child { margin-bottom: 0; }
    .table td { 
      padding: 10px 0; 
      border-bottom: 1px solid rgba(255,255,255,0.05); 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      white-space: normal;
    }
    .table td:last-child { border-bottom: none; padding-bottom: 0; }
    .table td:first-child { padding-top: 0; }
    .table td::before { 
      content: attr(data-label); 
      font-weight: 600; 
      color: var(--text-muted); 
      font-size: 11px; 
      text-transform: uppercase; 
    }
    .table-responsive { background: transparent; border: none; }
  }

  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
  }

  /* Modal Styles */
  .modal { display: none; position: fixed; inset: 0; z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
  .modal.open { display: flex; }
  .modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); }
  .modal-content { background: var(--surface); border: 1px solid var(--surface-border); padding: 24px; border-radius: 16px; width: 100%; max-width: 400px; position: relative; }
  .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
  .modal-close { background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 24px; }
</style>

<div class="purchase-container">

  @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid var(--success); padding: 16px; border-radius: 8px; margin-bottom: 24px; color: var(--success);">
      {{ session('success') }}
    </div>
  @endif

  <div class="header-actions">
    <div>
      <h2 class="page-title">My Purchases</h2>
      <p style="color: var(--text-muted); font-size: 14px; margin-top: 4px;">Manage and view your material purchases.</p>
    </div>
    <a href="{{ route('user.purchase.create') }}" class="btn-primary">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      Record Purchase
    </a>
  </div>

  <div class="filter-card">
    <form action="{{ route('user.purchase.index') }}" method="GET" class="filter-grid">
      <div>
        <input type="text" name="search" class="form-control" placeholder="Search Product, Vendor..." value="{{ request('search') }}">
      </div>
      <div>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
      </div>
      <div style="display: flex; gap: 8px;">
        <button type="submit" class="btn-primary" style="flex: 1; padding: 10px; justify-content: center; box-shadow: none;">Filter</button>
        <a href="{{ route('user.purchase.index') }}" class="btn-primary" style="flex: 1; padding: 10px; justify-content: center; background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--text); box-shadow: none;">Clear</a>
      </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Date</th>
          <th>ID</th>
          <th>Product</th>
          <th>Site</th>
          <th>Qty</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($purchases as $purchase)
        <tr>
          <td data-label="Date">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</td>
          <td data-label="ID"><span class="badge badge-info">{{ $purchase->unique_id_display }}</span></td>
          <td data-label="Product">
            <div style="font-weight: 500; color: var(--white);">{{ $purchase->product_name }}</div>
            @if($purchase->materialCode)
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Code: {{ $purchase->materialCode->code }}</div>
            @endif
          </td>
          <td data-label="Site">{{ $purchase->site ? $purchase->site->site_code : 'N/A' }}</td>
          <td data-label="Qty">
            @if($purchase->quantity)
              {{ $purchase->quantity }} <span style="color: var(--text-muted); font-size: 12px;">{{ $purchase->unit ? $purchase->unit->name : '' }}</span>
            @else
              -
            @endif
          </td>
          <td data-label="Amount" style="font-weight: 600; color: var(--white);">₹{{ number_format($purchase->amount, 2) }}</td>
          <td data-label="Status">
            @if($purchase->purchase_type === 'authorized')
              <span class="badge badge-success">Authorized</span>
            @else
              <span class="badge" style="background: rgba(255,255,255,0.1); color: var(--text); border: 1px solid var(--surface-border);">Unauthorized</span>
            @endif
          </td>
          <td data-label="Actions">
            <div style="display: flex; gap: 8px;">
              <a href="{{ route('user.purchase.edit', ['purchase' => $purchase->id, 'type' => $purchase->purchase_type]) }}" class="btn-icon" title="Edit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </a>
              @if($purchase->invoice_file)
                <a href="{{ asset('storage/' . $purchase->invoice_file) }}" target="_blank" class="btn-icon" title="View Invoice">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </a>
              @endif
              <form action="{{ route('user.purchase.destroy', ['purchase' => $purchase->id, 'type' => $purchase->purchase_type]) }}" method="POST" id="deleteForm_{{ $purchase->id }}_{{ $purchase->purchase_type }}" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-icon danger" title="Delete" onclick="openDeleteModal('deleteForm_{{ $purchase->id }}_{{ $purchase->purchase_type }}')">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="empty-state">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 16px; opacity: 0.5;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
              <p>No purchases found.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  
  <div style="margin-top: 20px;">
    {{ $purchases->links() }}
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
  <div class="modal-backdrop" onclick="closeModal('deleteModal')"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h3 class="modal-title" style="color: var(--danger);">Confirm Delete</h3>
      <button type="button" class="modal-close" onclick="closeModal('deleteModal')">×</button>
    </div>
    <div style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">
      Are you sure you want to delete this purchase record? This action cannot be undone.
    </div>
    <div style="display: flex; gap: 12px; justify-content: flex-end;">
      <button type="button" style="background: transparent; border: 1px solid var(--surface-border); color: var(--text); padding: 10px 20px; border-radius: 8px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'" onclick="closeModal('deleteModal')">Cancel</button>
      <button type="button" style="background: var(--danger); border: none; color: white; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600;" onclick="confirmDelete()">Yes, Delete</button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  let formToSubmit = null;

  function openDeleteModal(formId) {
    formToSubmit = document.getElementById(formId);
    document.getElementById('deleteModal').classList.add('open');
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('open');
  }

  function confirmDelete() {
    if (formToSubmit) {
      formToSubmit.submit();
    }
  }
</script>
@endpush
