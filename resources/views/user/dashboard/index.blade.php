@extends('user.layouts.app')

@section('content')
  <!-- LEFT COLUMN: ATM WALLET CARD, MONTHLY OVERVIEW STATS, TRANSACTIONS -->
  <div class="col-left">

    <!-- ATM CARD DESIGN WITH MULTI-BRANDING -->
    <section class="wallet-card">
      <div class="atm-header-row">
        <div class="atm-brand-ez">
          <span class="ez">EZ</span><span class="nirman">NIRMAN</span>
        </div>
        <div class="atm-brand-rc">Ranihati Construction Pvt. Ltd.</div>
      </div>

      <div class="atm-chip-row">
        <div class="atm-chip"></div>
        <div class="atm-contactless">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 8a10 10 0 0 1 14 0"></path><path d="M7.5 11.5a6 6 0 0 1 9 0"></path><path d="M10 15a2.5 2.5 0 0 1 4 0"></path></svg>
        </div>
      </div>

      <div class="atm-balance-display">
        <span class="wallet-label" style="font-size: 8px;">Available Credit Balance</span>
        <div class="wallet-balance" style="margin-top: 0; font-size: 32px;">
          <span class="currency">₹</span><span id="currentBalance">{{ number_format($wallet->current_balance, 2) }}</span>
        </div>
      </div>

      <!-- ATM Emulated Card Number -->
      <div class="atm-card-number">
        5021 &nbsp; 1107 &nbsp; 4{{ substr(Auth::user()->mobile ?? '9876543210', -4) }} &nbsp; 8847
      </div>

      <div class="atm-details-row">
        <div class="atm-holder-name">{{ strtoupper(Auth::user()->name) }}</div>
        <div class="atm-expiry">VAL THRU: 12/30</div>
      </div>
    </section>

    <!-- DYNAMIC TRANSACTION STATS THIS MONTH -->
    <section class="monthly-summary-card">
      <div class="summary-stat-box" style="border-right: 1px solid var(--surface-border); padding-right: 10px;">
        <span class="summary-stat-lbl">Monthly Transactions</span>
        <span class="summary-stat-val highlight">{{ $totalTransactionsCount }} Tx</span>
      </div>
      <div class="summary-stat-box" style="padding-left: 10px;">
        <span class="summary-stat-lbl">Total Volume</span>
        <span class="summary-stat-val">₹{{ number_format($totalTransactionsAmount, 2) }}</span>
      </div>
    </section>

    <!-- Monthly Limit Spent -->
    <section class="spend-metrics">
      <div class="metrics-header">
        <span class="metrics-title">Monthly Limit Spent</span>
        <span class="metrics-amount" id="metricsSummary">₹{{ number_format($monthlySpend, 2) }} / ₹60,000.00</span>
      </div>
      <div class="progress-track">
        <div class="progress-bar" id="metricsProgressBar" style="width: {{ min(($monthlySpend / 60000) * 100, 100) }}%"></div>
      </div>
      <div class="metrics-footer">
        <span>Usage Ratio: {{ number_format(min(($monthlySpend / 60000) * 100, 100), 1) }}%</span>
        <span>Available Limit: ₹{{ number_format(max(60000 - $monthlySpend, 0), 2) }}</span>
      </div>
    </section>

    <!-- Ledger List -->
    <section class="transactions-section" id="ledgerSection">
      <div class="section-header">
        <span class="section-title">Transactions Ledger</span>
        <div class="filter-tabs">
          <button class="filter-tab active" onclick="filterTransactions('all', this)">All</button>
          <button class="filter-tab" onclick="filterTransactions('credit', this)">In</button>
          <button class="filter-tab" onclick="filterTransactions('debit', this)">Out</button>
        </div>
      </div>

      <div class="ledger-list" id="ledgerList">
        @forelse($recentTransactions as $tx)
          <div class="ledger-item" data-type="{{ $tx->type }}" onclick="openReceiptModal('{{ $tx->note }}', 'EZ-TX-{{ $tx->id }}', '{{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}', 'Success', '{{ $tx->date->format('d M Y') }} · {{ $tx->created_at->format('h:i:s A') }}', '{{ $tx->accountcode ? $tx->accountcode->name : 'N/A' }}', '{{ $tx->note }}', '₹{{ number_format($tx->balance_after, 2) }}')">
            <div class="ledger-left">
              <div class="ledger-icon-wrap {{ $tx->type }}">
                {{ $tx->type === 'credit' ? '✓' : '↑' }}
              </div>
              <div class="ledger-details">
                <span class="ledger-desc">{{ $tx->note }}</span>
                <span class="ledger-sub">
                  {{ $tx->accountcode ? $tx->accountcode->name : 'General' }} · {{ $tx->date->format('d M Y') }} · {{ $tx->created_at->format('h:i:s A') }}
                </span>
                <span style="font-size: 10px; color: var(--text-muted); display: flex; align-items: center; gap: 4px; margin-top: 2px;">
                  <span style="width: 5px; height: 5px; background: var(--secondary); border-radius: 50%;"></span>
                  Balance After: ₹{{ number_format($tx->balance_after, 2) }}
                </span>
              </div>
            </div>
            <div class="ledger-right">
              <span class="ledger-amount {{ $tx->type }}">
                {{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
              </span>
              <span class="status-badge success">Success</span>
            </div>
          </div>
        @empty
          <div style="text-align: center; padding: 40px; color: var(--text-muted); font-size: 13px;">
            No transactions registered this month.
          </div>
        @endforelse
      </div>
    </section>

  </div>

  <!-- RIGHT COLUMN: QUICK ACTIONS, SITE WORKFLOWS, BUDGET DISTRIBUTION -->
  <div class="col-right">

    <!-- Quick Action Buttons -->
    <section class="quick-actions">
      <div class="action-btn" onclick="openModal('qrModal')">
        <div class="action-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
        </div>
        <span class="action-label">Scan QR</span>
      </div>

      <div class="action-btn" onclick="window.location.href='{{ route('user.sendmoney') }}'">
        <div class="action-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </div>
        <span class="action-label">Transfer</span>
      </div>

      <div class="action-btn" onclick="window.location.href='{{ route('user.addmoney') }}'">
        <div class="action-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </div>
        <span class="action-label">Request</span>
      </div>

      <div class="action-btn" id="historyScrollTrigger">
        <div class="action-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"></path><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"></path></svg>
        </div>
        <span class="action-label">History</span>
      </div>
    </section>

    <!-- Active Site Panel details -->
    <section class="site-panel-card">
      <div class="site-panel-header">
        <div class="site-icon">🏗️</div>
        <div style="display: flex; flex-direction: column;">
          <span class="site-meta-title">Project Site Alpha</span>
          <span class="site-meta-sub">Active Location Status Dashboard</span>
        </div>
      </div>

      <div class="checklist-section">
        <span class="check-title">Daily Checklist Status</span>
        
        <div class="check-item">
          <div class="check-box checked">✓</div>
          <span>Labour attendance uploaded</span>
        </div>

        <div class="check-item">
          <div class="check-box checked">✓</div>
          <span>Site logs checklist verified</span>
        </div>

        <div class="check-item">
          <div class="check-box"></div>
          <span style="color: var(--text-muted);">Cement logistics delivery validation</span>
        </div>
      </div>
    </section>

    <!-- Spend Breakdown Analytics -->
    <section class="budget-chart-card">
      <span class="check-title" style="margin-bottom: 4px;">Wallet Spend Distribution</span>
      
      <div class="chart-item">
        <div class="chart-lbl-row">
          <span class="chart-lbl">Materials Sourcing</span>
          <span class="chart-pct">68%</span>
        </div>
        <div class="chart-bar-track">
          <div class="chart-bar-fill" style="width: 68%; background: var(--secondary); box-shadow: 0 0 6px rgba(232,160,32,0.4);"></div>
        </div>
      </div>

      <div class="chart-item">
        <div class="chart-lbl-row">
          <span class="chart-lbl">Labour & Wages</span>
          <span class="chart-pct">22%</span>
        </div>
        <div class="chart-bar-track">
          <div class="chart-bar-fill" style="width: 22%; background: var(--primary); box-shadow: 0 0 6px rgba(124,111,247,0.4);"></div>
        </div>
      </div>

      <div class="chart-item">
        <div class="chart-lbl-row">
          <span class="chart-lbl">Logistics / Fuel Claim</span>
          <span class="chart-pct">10%</span>
        </div>
        <div class="chart-bar-track">
          <div class="chart-bar-fill" style="width: 10%; background: var(--success); box-shadow: 0 0 6px rgba(16,185,129,0.4);"></div>
        </div>
      </div>
    </section>

  </div>
@endsection

@push('scripts')
  <script>
    // Page specific scripts like transaction filtering
    function filterTransactions(type, btn) {
      const tabs = btn.parentNode.querySelectorAll('.filter-tab');
      tabs.forEach(t => t.classList.remove('active'));
      btn.classList.add('active');

      const items = document.querySelectorAll('.ledger-item');
      items.forEach(item => {
        if (type === 'all' || item.dataset.type === type) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Scroll trigger
    document.getElementById('historyScrollTrigger').addEventListener('click', () => {
      document.getElementById('ledgerSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  </script>
@endpush
