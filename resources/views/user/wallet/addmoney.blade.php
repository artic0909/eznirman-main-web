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
        <div class="site-icon" style="background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.2); color: var(--success); font-size: 20px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </div>
        <div style="display: flex; flex-direction: column;">
          <span class="site-meta-title" style="font-size: 18px;">Request Budget Allocation</span>
          <span class="site-meta-sub">Request funds for site operations</span>
        </div>
      </div>

      <form id="requestFormPage" onsubmit="handleRequestSubmitPage(event)" style="display: flex; flex-direction: column; gap: 24px;">

        <!-- Disbursal Date -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Request Date</label>
          <input type="date" id="requestDatePage" value="{{ date('Y-m-d') }}" required style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--success)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- From Name Reference -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Taken From</label>
          <input type="text" id="requestFromPage" placeholder="Enter Name"required style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--success)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- Required Amount -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Required Amount (₹)</label>
          <input type="number" placeholder="e.g. ₹15,000" min="1" required id="requestAmountPage" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--success)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- Justification Details -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
          <label style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Justification Details</label>
          <input type="text" placeholder="Provide justification remarks" required id="requestJustificationPage" style="background: var(--surface-light); border: 1px solid var(--surface-border); color: var(--white); font-family: var(--font-outfit); font-size: 14px; padding: 12px 16px; border-radius: 12px; outline: none; transition: border-color 0.25s;" onfocus="this.style.borderColor='var(--success)'" onblur="this.style.borderColor='var(--surface-border)'">
        </div>

        <!-- Submit Button -->
        <div style="margin-top: 10px;">
          <button type="submit" style="width: 100%; background: linear-gradient(95deg, var(--success) 0%, #34d399 100%); border: none; color: var(--white); font-family: var(--font-outfit); font-size: 14px; font-weight: 600; padding: 14px 28px; border-radius: 12px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);" onmouseover="this.style.opacity='0.95'; this.style.transform='translateY(-1px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
            Submit Request
          </button>
        </div>
      </form>
    </section>

  </div>
@endsection

@push('scripts')
<script>
    // Ajax Submit for Request Cash
    function handleRequestSubmitPage(event) {
      event.preventDefault();
      const date = document.getElementById('requestDatePage').value;
      const from = document.getElementById('requestFromPage').value.trim();
      const amount = parseFloat(document.getElementById('requestAmountPage').value);
      const justification = document.getElementById('requestJustificationPage').value.trim();

      fetch("{{ route('user.transaction.store') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
          amount: amount,
          type: 'credit',
          date: date,
          pay_to: 'from',
          pay_to_code: from,
          note: justification
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification('Request Submitted', `Site budget request of ₹${amount.toLocaleString('en-IN')} successfully submitted.`);
          document.getElementById('requestFormPage').reset();
          
          setTimeout(() => {
            window.location.href = "{{ route('user.dashboard') }}";
          }, 1500);
        } else {
          alert(data.message || 'Request submission failed.');
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert('Server connection error.');
      });
    }
</script>
@endpush
