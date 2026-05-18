<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eznirman — Accountant Login</title>
<link rel="icon" href="{{ asset('assets/images/logo.gif') }}" type="image/x-icon">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --steel:          #1a2332;
    --steel-mid:      #243044;
    --steel-light:    #2e3d54;
    --steel-border:   rgba(255,255,255,0.07);
    --amber:          #e8a020;
    --amber-pale:     #f5c85a;
    --teal:           #2eb89a;
    --teal-dim:       rgba(46,184,154,0.12);
    --teal-border:    rgba(46,184,154,0.3);
    --concrete:       #8fa0b0;
    --concrete-light: #c8d5df;
    --white:          #f4f6f8;
    --danger:         #e05c5c;
  }

  html, body { height: 100%; background: var(--steel); color: var(--white); font-family: 'DM Sans', sans-serif; font-weight: 300; overflow-x: hidden; }

  /* Dot grid bg */
  body::before {
    content: '';
    position: fixed; inset: 0;
    background-image: radial-gradient(rgba(46,184,154,0.12) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none; z-index: 0;
  }

  /* Glow blobs */
  .blob { position: fixed; border-radius: 50%; filter: blur(140px); pointer-events: none; z-index: 0; }
  .blob-1 { width: 480px; height: 480px; background: var(--teal); opacity: 0.07; top: -160px; left: -80px; }
  .blob-2 { width: 400px; height: 400px; background: var(--amber); opacity: 0.07; bottom: -100px; right: -80px; }

  /* ── Page: top nav + centered card ── */
  .topbar {
    position: fixed; top: 0; left: 0; right: 0;
    z-index: 50;
    height: 64px;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 40px;
    background: rgba(26,35,50,0.8);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(46,184,154,0.1);
  }

  .brand-logo { display: flex; align-items: baseline; gap: 2px; text-decoration: none; }
  .logo-ez   { font-family: 'Bebas Neue', sans-serif; font-size: 26px; color: var(--amber); letter-spacing: 2px; }
  .logo-nirman { font-family: 'Bebas Neue', sans-serif; font-size: 26px; color: var(--white); letter-spacing: 2px; }

  .topbar-role {
    display: flex; align-items: center; gap: 8px;
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
    color: var(--teal);
    border: 1px solid var(--teal-border);
    padding: 5px 14px;
  }
  .topbar-role::before {
    content: ''; width: 6px; height: 6px;
    background: var(--teal); border-radius: 50%;
    animation: pulse 2s infinite;
  }
  @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

  .topbar-back {
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete); text-decoration: none;
    display: flex; align-items: center; gap: 6px;
    transition: color 0.2s;
  }
  .topbar-back:hover { color: var(--white); }
  .topbar-back svg { transition: transform 0.2s; }
  .topbar-back:hover svg { transform: translateX(-3px); }

  /* ── Main layout ── */
  .page {
    position: relative; z-index: 1;
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    padding: 96px 24px 48px;
  }

  .card-wrap {
    width: 100%; max-width: 960px;
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    border: 1px solid rgba(46,184,154,0.12);
    background: rgba(26,35,50,0.6);
    backdrop-filter: blur(20px);
    animation: fadeUp 0.7s ease both;
    overflow: hidden;
  }

  @keyframes fadeUp { from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:translateY(0);} }

  /* ── Left info strip ── */
  .info-strip {
    background: rgba(46,184,154,0.05);
    border-right: 1px solid rgba(46,184,154,0.1);
    padding: 48px 40px;
    display: flex; flex-direction: column; justify-content: space-between;
    position: relative; overflow: hidden;
  }

  .info-strip::after {
    content: '₹';
    position: absolute; bottom: -30px; right: -10px;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 220px; line-height: 1;
    color: rgba(46,184,154,0.04);
    pointer-events: none;
  }

  .role-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--teal-dim);
    border: 1px solid var(--teal-border);
    padding: 6px 14px; margin-bottom: 28px;
    width: fit-content;
  }
  .role-badge svg { color: var(--teal); }
  .role-badge span {
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--teal);
  }

  .info-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(34px, 3.5vw, 52px);
    line-height: 0.95; letter-spacing: 1px;
    margin-bottom: 16px;
  }
  .info-title .teal { color: var(--teal); }

  .info-desc {
    font-size: 13px; line-height: 1.7;
    color: var(--concrete); margin-bottom: 36px;
    max-width: 280px;
  }

  /* Access pills */
  .access-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 36px; }
  .access-item {
    display: flex; align-items: center; gap: 10px;
    font-size: 12px; color: var(--concrete-light);
  }
  .access-dot {
    width: 7px; height: 7px; border-radius: 50%;
    flex-shrink: 0;
  }
  .dot-on  { background: var(--teal); box-shadow: 0 0 6px rgba(46,184,154,0.5); }
  .dot-off { background: var(--steel-light); border: 1px solid var(--steel-border); }

  .info-footer {
    font-family: 'DM Mono', monospace;
    font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete); opacity: 0.4;
    padding-top: 24px;
    border-top: 1px solid rgba(46,184,154,0.08);
  }

  /* ── Form panel ── */
  .form-panel { padding: 48px 44px; display: flex; flex-direction: column; justify-content: center; }

  .form-head { margin-bottom: 32px; }
  .form-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 36px; letter-spacing: 1px; margin-bottom: 4px;
  }
  .form-sub { font-size: 13px; color: var(--concrete); line-height: 1.5; }

  /* Segment: company ID */
  .segment {
    display: flex; gap: 0;
    margin-bottom: 20px;
    border: 1px solid var(--steel-border);
    overflow: hidden;
    transition: border-color 0.2s;
  }
  .segment-label {
    background: rgba(46,184,154,0.08);
    border-right: 1px solid var(--steel-border);
    padding: 0 14px;
    display: flex; align-items: center;
    font-family: 'DM Mono', monospace;
    font-size: 11px; letter-spacing: 1px; text-transform: uppercase;
    color: var(--teal); white-space: nowrap; flex-shrink: 0;
  }
  .segment input {
    flex: 1; background: var(--steel-mid);
    border: none; outline: none;
    color: var(--white); font-family: 'DM Sans', sans-serif;
    font-size: 14px; padding: 12px 14px;
    transition: background 0.2s;
  }
  .segment input::placeholder { color: var(--concrete); opacity: 0.5; }
  .segment:focus-within { border-color: var(--teal); }
  .segment:focus-within .segment-label { color: var(--teal); background: rgba(46,184,154,0.14); }
  .segment:focus-within input { background: rgba(36,48,68,0.9); }

  .form-group { margin-bottom: 18px; }
  .form-label {
    display: block;
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete); margin-bottom: 7px;
  }

  .input-wrap { position: relative; display: flex; align-items: center; }
  .input-icon {
    position: absolute; left: 13px; color: var(--concrete);
    display: flex; align-items: center; pointer-events: none; transition: color 0.2s;
  }
  .input-wrap:focus-within .input-icon { color: var(--teal); }

  .form-input {
    width: 100%; background: var(--steel-mid);
    border: 1px solid var(--steel-border);
    color: var(--white); font-family: 'DM Sans', sans-serif;
    font-size: 14px; font-weight: 400;
    padding: 12px 16px 12px 40px;
    outline: none; transition: border-color 0.25s, background 0.25s;
    -webkit-appearance: none; border-radius: 0;
  }
  .form-input::placeholder { color: var(--concrete); opacity: 0.5; }
  .form-input:focus { border-color: var(--teal); background: rgba(36,48,68,0.9); }
  .form-input.error { border-color: var(--danger); }

  .toggle-pw {
    position: absolute; right: 12px;
    background: none; border: none; color: var(--concrete);
    cursor: pointer; display: flex; align-items: center; padding: 4px;
    transition: color 0.2s;
  }
  .toggle-pw:hover { color: var(--teal); }

  .error-msg {
    display: none; font-size: 11px; color: var(--danger);
    margin-top: 5px; font-family: 'DM Mono', monospace; letter-spacing: 0.5px;
  }
  .error-msg.show { display: block; }

  /* Two-col row */
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

  .form-meta {
    display: flex; align-items: center; justify-content: space-between;
    margin: 20px 0 24px;
  }
  .remember {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--concrete); cursor: pointer; user-select: none;
  }
  .remember input[type="checkbox"] {
    width: 16px; height: 16px;
    appearance: none; -webkit-appearance: none;
    background: var(--steel-mid);
    border: 1px solid var(--steel-border);
    cursor: pointer; position: relative; flex-shrink: 0;
    transition: background 0.2s, border-color 0.2s;
  }
  .remember input:checked { background: var(--teal); border-color: var(--teal); }
  .remember input:checked::after {
    content: ''; position: absolute; top: 2px; left: 5px;
    width: 4px; height: 8px;
    border: 2px solid var(--steel); border-top: none; border-left: none;
    transform: rotate(45deg);
  }
  .forgot-link {
    font-size: 12px; font-family: 'DM Mono', monospace;
    letter-spacing: 1px; text-transform: uppercase;
    color: var(--concrete); text-decoration: none; transition: color 0.2s;
  }
  .forgot-link:hover { color: var(--teal); }

  /* Alert */
  .alert {
    display: none; align-items: center; gap: 10px;
    padding: 11px 14px; margin-bottom: 18px;
    border-left: 3px solid var(--danger);
    background: rgba(224,92,92,0.08);
    font-size: 13px; color: #f08080;
  }
  .alert.show { display: flex; }

  /* Submit */
  .btn-login {
    width: 100%;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--steel); background: var(--teal);
    border: none; padding: 14px;
    cursor: pointer; position: relative;
    transition: background 0.2s, transform 0.15s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
  }
  .btn-login:hover { background: #39d4b2; transform: translateY(-1px); }
  .btn-login:active { transform: translateY(0); }
  .btn-login:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

  .spinner {
    display: none; width: 16px; height: 16px;
    border: 2px solid rgba(26,35,50,0.3); border-top-color: var(--steel);
    border-radius: 50%; animation: spin 0.7s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
  .btn-login.loading .btn-text { display: none; }
  .btn-login.loading .spinner { display: block; }

  .form-footer {
    margin-top: 20px; text-align: center;
    font-size: 12px; color: var(--concrete);
  }
  .form-footer a { color: var(--teal); text-decoration: none; }
  .form-footer a:hover { text-decoration: underline; }

  /* ── Responsive ── */
  @media (max-width: 860px) {
    .card-wrap { grid-template-columns: 1fr; max-width: 480px; }
    .info-strip { display: none; }
    .form-panel { padding: 40px 32px; }
    .form-row { grid-template-columns: 1fr; gap: 0; }
  }

  @media (max-width: 520px) {
    .topbar { padding: 0 20px; }
    .topbar-back span { display: none; }
    .form-panel { padding: 32px 20px; }
    .page { padding: 80px 12px 40px; }
    .form-meta { flex-direction: column; align-items: flex-start; gap: 12px; }
    .segment-label { display: none; }
    .segment { border: 1px solid var(--steel-border); }
    .segment input { padding-left: 14px; }
  }
</style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<!-- TOP BAR -->
<header class="topbar">
  <a href="{{ route('home') }}" class="brand-logo">
    <span class="logo-ez">EZ</span><span class="logo-nirman">NIRMAN</span>
  </a>
  <div class="topbar-role">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Accountant Portal
  </div>
  <a href="{{ route('home') }}" class="topbar-back">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
    <span>Back to Home</span>
  </a>
</header>

<!-- MAIN -->
<main class="page">
  <div class="card-wrap">

    <!-- INFO STRIP -->
    <div class="info-strip">
      <div>
        <div class="role-badge">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          <span>Accounts Access</span>
        </div>
        <h2 class="info-title">Finance<br><span class="teal">Command</span><br>Centre</h2>
        <p class="info-desc">Manage budgets, invoices, GST reports and cash flows across all active construction projects.</p>

        <div class="access-list">
          <div class="access-item"><span class="access-dot dot-on"></span>Budget & expense tracking</div>
          <div class="access-item"><span class="access-dot dot-on"></span>Invoice & payment management</div>
          <div class="access-item"><span class="access-dot dot-on"></span>GST & tax report generation</div>
          <div class="access-item"><span class="access-dot dot-on"></span>Vendor & contractor bills</div>
          <div class="access-item"><span class="access-dot dot-off"></span>Site management (restricted)</div>
          <div class="access-item"><span class="access-dot dot-off"></span>Admin settings (restricted)</div>
        </div>
      </div>

      <div class="info-footer">Secure · Role-restricted · eznirman.com</div>
    </div>

    <!-- FORM PANEL -->
    <div class="form-panel">
      <div class="form-head">
        <h1 class="form-title">Accountant Sign In</h1>
        <p class="form-sub">Enter your credentials to access the finance dashboard.</p>
      </div>

      <!-- Alert -->
      @if (session('success') || session('error') || $errors->any())
        <div class="alert show" id="loginAlert" style="display: flex; @if(session('success')) border-left-color: var(--teal); background: rgba(46,184,154,0.08); color: var(--teal); @endif">
          @if (session('success'))
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span id="alertMsg">{{ session('success') }}</span>
          @elseif (session('error'))
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="alertMsg">{{ session('error') }}</span>
          @elseif ($errors->any())
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="alertMsg">{{ $errors->first() }}</span>
          @endif
        </div>
      @else
        <div class="alert" id="loginAlert">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="alertMsg">Invalid credentials. Please check and try again.</span>
        </div>
      @endif

      <form id="loginForm" action="{{ route('account.login.verify') }}" method="POST" novalidate>
        @csrf

        <!-- Email -->
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <div class="segment" id="emailSegment">
            <span class="segment-label">Email —</span>
            <input type="email" id="email" name="email" placeholder="e.g. email@mail.com" value="{{ old('email') }}" />
          </div>
          <div class="error-msg" id="emailError">Please enter a valid email address.</div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input class="form-input" type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" />
            <button type="button" class="toggle-pw" id="togglePw" aria-label="Toggle password">
              <svg id="eyeIcon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="error-msg" id="passwordError">Password is required.</div>
        </div>

        <!-- Meta -->
        <div class="form-meta">
          <label class="remember">
            <input type="checkbox" id="remember" name="remember" />
            Keep me signed in
          </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-login" id="loginBtn">
          <span class="btn-text">Access Finance Dashboard</span>
          <div class="spinner"></div>
        </button>
      </form>

      <div class="form-footer">
        Wrong portal? <a href="{{ route('admin.login') }}">Admin Portal</a> &nbsp;·&nbsp; <a href="{{ route('home') }}">Home</a>
      </div>
    </div>

  </div>
</main>

<script>
  const form    = document.getElementById('loginForm');
  const btn     = document.getElementById('loginBtn');
  const alertEl = document.getElementById('loginAlert');
  const alertMsg= document.getElementById('alertMsg');
  const togglePw= document.getElementById('togglePw');
  const eyeIcon = document.getElementById('eyeIcon');
  const passEl  = document.getElementById('password');
  const emailEl = document.getElementById('email');

  togglePw.addEventListener('click', () => {
    const show = passEl.type === 'password';
    passEl.type = show ? 'text' : 'password';
    eyeIcon.innerHTML = show
      ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
      : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  });

  function setErr(id, inputId, show) {
    document.getElementById(id).classList.toggle('show', show);
    const el = document.getElementById(inputId);
    if (el) {
      if (inputId === 'email') {
        document.getElementById('emailSegment').style.borderColor = show ? 'var(--danger)' : '';
      } else {
        el.classList.toggle('error', show);
      }
    }
  }

  emailEl.addEventListener('input', () => {
    document.getElementById('emailError').classList.remove('show');
    document.getElementById('emailSegment').style.borderColor = '';
  });

  passEl.addEventListener('input', () => {
    document.getElementById('passwordError').classList.remove('show');
    passEl.classList.remove('error');
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    if (alertEl) {
      alertEl.classList.remove('show');
      alertEl.style.display = 'none'; // reset default layout
    }

    let ok = true;
    const email = emailEl.value.trim();
    const pass  = passEl.value;

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setErr('emailError','email',true); ok=false; }
    if (!pass) { setErr('passwordError','password',true); ok=false; }
    if (!ok) return;

    btn.classList.add('loading');
    btn.disabled = true;

    // Submit form to Laravel backend
    form.submit();
  });

  // Auto-dismiss backend alerts after 5 seconds
  document.addEventListener('DOMContentLoaded', () => {
    if (alertEl && alertEl.classList.contains('show')) {
      setTimeout(() => {
        alertEl.style.transition = 'opacity 0.4s ease';
        alertEl.style.opacity = '0';
        setTimeout(() => {
          alertEl.classList.remove('show');
          alertEl.style.opacity = '1';
        }, 400);
      }, 5000);
    }
  });
</script>
</body>
</html>
