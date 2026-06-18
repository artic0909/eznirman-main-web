@extends('user.layouts.app')

@section('content')
  <!-- LEFT COLUMN: LEDGER HISTORY -->
  <div class="col-left" style="grid-column: 1 / -1;">

    <!-- TOP SECTION SUMMARY -->
    <section class="monthly-summary-card" style="grid-template-columns: 1fr 1fr; margin-bottom: 8px;">
      <div class="summary-stat-box" style="border-right: 1px solid var(--surface-border); padding-right: 10px;">
        <span class="summary-stat-lbl">Wallet Balance</span>
        <span class="summary-stat-val" style="color: var(--secondary); font-size: 24px;">₹{{ number_format($wallet->current_balance, 2) }}</span>
      </div>
      <div class="summary-stat-box" style="padding-left: 10px;">
        <span class="summary-stat-lbl">Debit History</span>
        <span class="summary-stat-val highlight" style="color: var(--danger); font-size: 24px;">{{ $debits->total() }} entries</span>
      </div>
    </section>

    <!-- Debits Ledger List -->
    <section class="transactions-section">
      <div class="section-header">
        <span class="section-title">All Debits</span>
        <div style="display: flex; gap: 8px;">
          <input type="text" id="debitSearch" placeholder="Search disbursals..." onkeyup="searchDebits()" style="background: var(--surface); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 12px; padding: 6px 12px; border-radius: 10px; outline: none; width: 180px;">
        </div>
      </div>

      <div class="ledger-list" id="debitLedgerList">
        @forelse($debits as $tx)
          <div class="ledger-item debit-row" data-note="{{ strtolower($tx->note) }}" onclick="openReceiptModal('{{ $tx->note }}', 'EZ-TX-{{ $tx->id }}', '-₹{{ number_format($tx->amount, 2) }}', 'Success', '{{ $tx->date->format('d M Y') }} · {{ $tx->created_at->format('h:i:s A') }}', '{{ $tx->accountcode ? $tx->accountcode->name : 'N/A' }}', '{{ $tx->note }}', '₹{{ number_format($tx->balance_after, 2) }}')">
            <div class="ledger-left">
              <div class="ledger-icon-wrap debit">↑</div>
              <div class="ledger-details">
                <span class="ledger-desc">{{ $tx->note }}</span>
                <span class="ledger-sub">
                  {{ $tx->accountcode ? $tx->accountcode->name : 'General Disbursal' }} · {{ $tx->date->format('d M Y') }} · {{ $tx->created_at->format('h:i:s A') }}
                </span>
                <span style="font-size: 10px; color: var(--text-muted); display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                  <span style="width: 5px; height: 5px; background: var(--secondary); border-radius: 50%;"></span>
                  Balance After: ₹{{ number_format($tx->balance_after, 2) }}
                </span>
              </div>
            </div>
            <div class="ledger-right">
              <span class="ledger-amount debit">₹{{ number_format($tx->amount, 2) }}</span>
              <span class="status-badge success">Success</span>
            </div>
          </div>
        @empty
          <div style="text-align: center; padding: 60px; color: var(--text-muted); font-size: 13px;">
            No debit disbursals registered.
          </div>
        @endforelse
      </div>

      <!-- Pagination block -->
      <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $debits->links('pagination::bootstrap-5') }}
      </div>
    </section>

  </div>
@endsection

@push('scripts')
  <script>
    function searchDebits() {
      const q = document.getElementById('debitSearch').value.toLowerCase();
      const rows = document.querySelectorAll('.debit-row');
      rows.forEach(row => {
        const note = row.dataset.note;
        if (note.includes(q)) {
          row.style.display = 'flex';
        } else {
          row.style.display = 'none';
        }
      });
    }
  </script>
  <style>
    /* Styling to make pagination custom indicators look gorgeous in dark mode */
    .pagination {
      display: flex;
      gap: 6px;
      list-style: none;
    }
    .pagination .page-item .page-link {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      color: var(--text-muted);
      padding: 8px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 12px;
      transition: all 0.2s;
    }
    .pagination .page-item.active .page-link {
      background: var(--primary);
      color: var(--white);
      border-color: var(--primary);
    }
    .pagination .page-item .page-link:hover {
      background: var(--surface-light);
      color: var(--white);
    }
  </style>
@endpush
