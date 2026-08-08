@extends('user.layouts.app')

@section('content')
  <div class="col-left" style="grid-column: 1 / -1;">

    @if(session('success'))
      <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: var(--success); padding: 12px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
        ✓ {{ session('success') }}
      </div>
    @endif

    <section class="site-panel-card" style="padding: 32px; border-radius: 24px; max-width: 600px; margin: 0 auto;">
      <div class="site-panel-header" style="border-bottom: 1px solid var(--surface-border); padding-bottom: 20px; margin-bottom: 24px;">
        <div class="site-icon" style="background: rgba(124, 111, 247, 0.08); border-color: rgba(124, 111, 247, 0.2); color: var(--primary); font-size: 20px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </div>
        <div style="display: flex; flex-direction: column;">
          <span class="site-meta-title" style="font-size: 18px;">Transfer Funds</span>
          <span class="site-meta-sub">Disburse payments to workers or contractors</span>
        </div>
      </div>

      <form id="transferFormPage" onsubmit="handleTransferSubmitPage(event)" style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Disbursal Date -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Disbursal Date</label>
          <input type="date" id="transferDatePage" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- Pay To Choice -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Pay To</label>
          <select id="transferPayToPage" required onchange="togglePayToFieldsPage()" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
            <option value="worker" selected>Worker</option>
            <option value="contractor">Contractor</option>
            <option value="others">Others</option>
          </select>
        </div>

        <!-- Conditional Worker search via Select2 -->
        <div id="workerCodeGroupPage" style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Worker Code & Name</label>
          <select class="select2-worker-page" id="transferWorkerCodePage" style="width: 100%;">
            <option value="" disabled selected>Search Worker Code or Name...</option>
            @foreach($workers as $worker)
              <option value="{{ $worker->code }}">{{ $worker->code }} — {{ $worker->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Conditional Contractor reference -->
        <div id="contractorCodeGroupPage" style="display: none; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Contractor Name / Reference</label>
          <input type="text" id="transferContractorCodePage" placeholder="Enter contractor name or code" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- Category Reference -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Category Reference</label>
          <select required id="transferAccountCodePage" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
            @foreach($accountCodes as $ac)
              <option value="{{ $ac->id }}">{{ $ac->code }} — {{ $ac->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Amount -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Transfer Amount (₹)</label>
          <input type="number" placeholder="Enter amount" min="1" required id="transferAmountPage" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- Remarks -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Remarks / Purpose</label>
          <input type="text" placeholder="e.g. concrete cement or local wages" required id="transferRemarksPage" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- Submit Button -->
        <div style="margin-top: 10px;">
          <button type="submit" style="width: 100%; background: linear-gradient(95deg, var(--primary) 0%, #a59ef9 100%); border: none; color: var(--white); font-family: var(--font-outfit); font-size: 14px; font-weight: 600; padding: 14px 28px; border-radius: 12px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 14px var(--primary-glow);" onmouseover="this.style.opacity='0.95'; this.style.transform='translateY(-1px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
            Confirm Disbursal
          </button>
        </div>
      </form>
    </section>

  </div>
@endsection

@push('scripts')
<script>
    // Toggle conditional fields based on Pay To selection
    function togglePayToFieldsPage() {
      const payTo = document.getElementById('transferPayToPage').value;
      const workerGroup = document.getElementById('workerCodeGroupPage');
      const contractorGroup = document.getElementById('contractorCodeGroupPage');
      
      workerGroup.style.display = 'none';
      contractorGroup.style.display = 'none';
      
      if (payTo === 'worker') {
        workerGroup.style.display = 'flex';
      } else if (payTo === 'contractor') {
        contractorGroup.style.display = 'flex';
      }
    }

    // Initialize Select2 search dropdown on document ready
    $(document).ready(function() {
      if (typeof $.fn.select2 !== 'undefined') {
        $('.select2-worker-page').select2({
          placeholder: "Search Worker Code or Name..."
        });
      }
      togglePayToFieldsPage();
    });

    // Ajax Submit for Transfer Funds
    function handleTransferSubmitPage(event) {
      event.preventDefault();
      const date = document.getElementById('transferDatePage').value;
      const pay_to = document.getElementById('transferPayToPage').value;
      
      let pay_to_code = '';
      if (pay_to === 'worker') {
        pay_to_code = document.getElementById('transferWorkerCodePage').value;
        if (!pay_to_code) {
          alert('Please select a Worker Code from the search list.');
          return;
        }
      } else if (pay_to === 'contractor') {
        pay_to_code = document.getElementById('transferContractorCodePage').value.trim();
        if (!pay_to_code) {
          alert('Please enter a Contractor Name or Reference.');
          return;
        }
      }

      const accountcode_id = document.getElementById('transferAccountCodePage').value;
      const amount = parseFloat(document.getElementById('transferAmountPage').value);
      const remarks = document.getElementById('transferRemarksPage').value.trim();

      fetch("{{ route('user.transaction.store') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          amount: amount,
          type: 'debit',
          date: date,
          pay_to: pay_to,
          pay_to_code: pay_to_code,
          note: remarks,
          accountcode_id: accountcode_id
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification('Disbursal Successful', `Sent ₹${amount.toLocaleString('en-IN')} to ${pay_to.toUpperCase()} ${pay_to_code}`);
          document.getElementById('transferFormPage').reset();
          
          setTimeout(() => {
            window.location.href = "{{ route('user.dashboard') }}";
          }, 1500);
        } else {
          alert(data.message || 'Transfer processing failed.');
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert('Server connection error.');
      });
    }
</script>
@endpush
