<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eznirman — Admin Login</title>
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
    --concrete:       #8fa0b0;
    --concrete-light: #c8d5df;
    --white:          #f4f6f8;
    --accent-line:    rgba(232,160,32,0.3);
    --danger:         #e05c5c;
  }

  html, body {
    height: 100%;
    background: var(--steel);
    color: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-weight: 300;
    overflow-x: hidden;
  }

  /* Grid bg */
  body::before {
    content: '';
    position: fixed; inset: 0;
    background-image:
      linear-gradient(rgba(232,160,32,0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(232,160,32,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    z-index: 0;
  }

  /* Glow blobs */
  .blob {
    position: fixed;
    border-radius: 50%;
    filter: blur(130px);
    pointer-events: none;
    z-index: 0;
  }
  .blob-1 { width: 500px; height: 500px; background: var(--amber); opacity: 0.1; top: -180px; right: -120px; }
  .blob-2 { width: 360px; height: 360px; background: #3a7bd5; opacity: 0.08; bottom: -80px; left: -100px; }

  /* ── Layout ── */
  .page {
    position: relative;
    z-index: 1;
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
  }

  /* ── Left Panel ── */
  .left-panel {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 48px 56px;
    background: rgba(26,35,50,0.5);
    border-right: 1px solid rgba(232,160,32,0.08);
    position: relative;
    overflow: hidden;
  }

  .left-panel::after {
    content: 'ADMIN';
    position: absolute;
    bottom: -20px;
    left: -10px;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 180px;
    color: rgba(232,160,32,0.03);
    letter-spacing: 8px;
    pointer-events: none;
    line-height: 1;
  }

  .brand { display: flex; flex-direction: column; gap: 8px; }
  .brand-logo { display: flex; align-items: baseline; gap: 2px; }
  .logo-ez {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 32px; color: var(--amber); letter-spacing: 2px;
  }
  .logo-nirman {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 32px; color: var(--white); letter-spacing: 2px;
  }
  .brand-sub {
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
    color: var(--concrete); margin-top: 2px;
  }

  .left-content { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 60px 0; }

  .left-headline {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(40px, 4vw, 64px);
    line-height: 0.95;
    letter-spacing: 1px;
    margin-bottom: 20px;
  }
  .left-headline .amber { color: var(--amber); }
  .left-headline .dim { color: var(--steel-light); -webkit-text-stroke: 1px var(--concrete); }

  .left-desc {
    font-size: 14px; line-height: 1.7;
    color: var(--concrete); max-width: 340px; margin-bottom: 40px;
  }

  .feature-list { list-style: none; display: flex; flex-direction: column; gap: 14px; }
  .feature-list li {
    display: flex; align-items: center; gap: 12px;
    font-size: 13px; color: var(--concrete-light);
  }
  .feature-list li::before {
    content: '';
    flex-shrink: 0;
    width: 20px; height: 20px;
    border: 1px solid var(--accent-line);
    display: flex; align-items: center; justify-content: center;
    background: rgba(232,160,32,0.08);
  }
  .check-icon { color: var(--amber); font-size: 11px; }

  .left-footer {
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 1px;
    color: var(--concrete); opacity: 0.5;
  }

  /* ── Right Panel ── */
  .right-panel {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 56px;
    position: relative;
  }

  .login-box {
    width: 100%;
    max-width: 400px;
    animation: fadeUp 0.7s ease both;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .login-tag {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
    color: var(--amber);
    border: 1px solid var(--accent-line);
    padding: 5px 12px;
    margin-bottom: 28px;
  }
  .login-tag::before {
    content: '';
    width: 6px; height: 6px;
    background: var(--amber);
    border-radius: 50%;
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0%,100% { opacity:1; } 50% { opacity:0.3; }
  }

  .login-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 42px; letter-spacing: 1px;
    margin-bottom: 6px;
  }
  .login-subtitle {
    font-size: 14px; color: var(--concrete);
    margin-bottom: 36px; line-height: 1.5;
  }

  /* ── Form ── */
  .form-group { margin-bottom: 20px; }

  .form-label {
    display: block;
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete); margin-bottom: 8px;
  }

  .input-wrap {
    position: relative;
    display: flex; align-items: center;
  }
  .input-icon {
    position: absolute; left: 14px;
    color: var(--concrete);
    display: flex; align-items: center;
    pointer-events: none;
    transition: color 0.2s;
  }
  .input-wrap:focus-within .input-icon { color: var(--amber); }

  .form-input {
    width: 100%;
    background: var(--steel-mid);
    border: 1px solid var(--steel-border);
    color: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-size: 14px; font-weight: 400;
    padding: 13px 16px 13px 42px;
    outline: none;
    border-radius: 0;
    transition: border-color 0.25s, background 0.25s;
    -webkit-appearance: none;
  }
  .form-input::placeholder { color: var(--concrete); opacity: 0.5; }
  .form-input:focus {
    border-color: var(--amber);
    background: rgba(36,48,68,0.9);
  }
  .form-input.error { border-color: var(--danger); }

  /* Password toggle */
  .toggle-pw {
    position: absolute; right: 14px;
    background: none; border: none;
    color: var(--concrete); cursor: pointer;
    display: flex; align-items: center;
    padding: 4px;
    transition: color 0.2s;
  }
  .toggle-pw:hover { color: var(--amber); }

  /* Error msg */
  .error-msg {
    display: none;
    font-size: 12px; color: var(--danger);
    margin-top: 6px;
    font-family: 'DM Mono', monospace;
    letter-spacing: 0.5px;
  }
  .error-msg.show { display: block; }

  /* Forgot */
  .form-meta {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 28px;
  }
  .remember {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--concrete);
    cursor: pointer; user-select: none;
  }
  .remember input[type="checkbox"] {
    width: 16px; height: 16px;
    appearance: none; -webkit-appearance: none;
    background: var(--steel-mid);
    border: 1px solid var(--steel-border);
    cursor: pointer;
    position: relative;
    flex-shrink: 0;
    transition: background 0.2s, border-color 0.2s;
  }
  .remember input[type="checkbox"]:checked {
    background: var(--amber);
    border-color: var(--amber);
  }
  .remember input[type="checkbox"]:checked::after {
    content: '';
    position: absolute; top: 2px; left: 5px;
    width: 4px; height: 8px;
    border: 2px solid var(--steel);
    border-top: none; border-left: none;
    transform: rotate(45deg);
  }

  .forgot-link {
    font-size: 12px; font-family: 'DM Mono', monospace;
    letter-spacing: 1px; text-transform: uppercase;
    color: var(--concrete); text-decoration: none;
    transition: color 0.2s;
  }
  .forgot-link:hover { color: var(--amber); }

  /* Submit */
  .btn-login {
    width: 100%;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    color: var(--steel);
    background: var(--amber);
    border: none;
    padding: 15px;
    cursor: pointer;
    clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%);
    transition: background 0.2s, transform 0.15s;
    position: relative;
    overflow: hidden;
  }
  .btn-login:hover { background: var(--amber-pale); transform: translateY(-1px); }
  .btn-login:active { transform: translateY(0); }
  .btn-login:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

  /* Loading spinner inside button */
  .btn-login .spinner {
    display: none;
    width: 16px; height: 16px;
    border: 2px solid rgba(26,35,50,0.3);
    border-top-color: var(--steel);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    margin: 0 auto;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
  .btn-login.loading .btn-text { display: none; }
  .btn-login.loading .spinner { display: block; }

  /* Alert */
  .alert {
    display: none;
    align-items: center; gap: 10px;
    padding: 12px 16px;
    margin-bottom: 20px;
    border-left: 3px solid var(--danger);
    background: rgba(224,92,92,0.08);
    font-size: 13px; color: #f08080;
  }
  .alert.show { display: flex; }
  .alert svg { flex-shrink: 0; }

  /* Divider */
  .divider {
    display: flex; align-items: center; gap: 14px;
    margin: 28px 0;
  }
  .divider::before, .divider::after {
    content: ''; flex: 1;
    height: 1px; background: var(--steel-border);
  }
  .divider span {
    font-family: 'DM Mono', monospace;
    font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete); opacity: 0.5; white-space: nowrap;
  }

  .login-footer {
    margin-top: 32px; text-align: center;
    font-size: 12px; color: var(--concrete);
  }
  .login-footer a { color: var(--amber); text-decoration: none; }
  .login-footer a:hover { text-decoration: underline; }

  /* ── Version badge ── */
  .version-badge {
    position: absolute; bottom: 24px; right: 24px;
    font-family: 'DM Mono', monospace;
    font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete); opacity: 0.4;
  }

  /* ── Mobile logo (hidden on desktop) ── */
  .mobile-brand {
    display: none;
    align-items: center; gap: 4px;
    margin-bottom: 32px;
  }

  /* ── Responsive ── */
  @media (max-width: 900px) {
    .page { grid-template-columns: 1fr; }
    .left-panel { display: none; }
    .right-panel { padding: 40px 24px; min-height: 100vh; justify-content: flex-start; padding-top: 60px; }
    .login-box { max-width: 100%; }
    .mobile-brand { display: flex; }
    .version-badge { position: static; text-align: center; margin-top: 24px; display: block; }
  }

  @media (max-width: 400px) {
    .right-panel { padding: 40px 16px; }
    .form-meta { flex-direction: column; align-items: flex-start; gap: 12px; }
    .login-title { font-size: 36px; }
  }
</style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<div class="page">

  <!-- ── LEFT PANEL ── -->
  <div class="left-panel">
    <div class="brand">
      <div class="brand-logo">
        <span class="logo-ez">EZ</span><span class="logo-nirman">NIRMAN</span>
      </div>
      <div class="brand-sub">Construction Management System</div>
    </div>

    <div class="left-content">
      <h2 class="left-headline">
        Command<br>
        <span class="amber">Your</span><br>
        <span class="dim">Projects.</span>
      </h2>
      <p class="left-desc">
        Full visibility across every site, team, and budget — from a single secure admin console.
      </p>
      <ul class="feature-list">
        <li>
          <svg class="check-icon" width="11" height="11" viewBox="0 0 12 12" fill="none"><polyline points="1.5 6 5 9.5 10.5 2.5" stroke="#e8a020" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Real-time project dashboards
        </li>
        <li>
          <svg class="check-icon" width="11" height="11" viewBox="0 0 12 12" fill="none"><polyline points="1.5 6 5 9.5 10.5 2.5" stroke="#e8a020" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Labour & material tracking
        </li>
        <li>
          <svg class="check-icon" width="11" height="11" viewBox="0 0 12 12" fill="none"><polyline points="1.5 6 5 9.5 10.5 2.5" stroke="#e8a020" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          GST-ready financial reports
        </li>
        <li>
          <svg class="check-icon" width="11" height="11" viewBox="0 0 12 12" fill="none"><polyline points="1.5 6 5 9.5 10.5 2.5" stroke="#e8a020" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Role-based access control
        </li>
      </ul>
    </div>

    <div class="left-footer">© {{ date('Y') }} eznirman.com — All rights reserved</div>
  </div>

  <!-- ── RIGHT PANEL ── -->
  <div class="right-panel">
    <div class="login-box">

      <!-- Mobile only brand -->
      <div class="mobile-brand">
        <span class="logo-ez" style="font-family:'Bebas Neue',sans-serif;font-size:26px;color:var(--amber);letter-spacing:2px;">EZ</span>
        <span class="logo-nirman" style="font-family:'Bebas Neue',sans-serif;font-size:26px;color:var(--white);letter-spacing:2px;">NIRMAN</span>
      </div>

      <div class="login-tag">Admin Portal</div>

      <h1 class="login-title">Welcome Back</h1>
      <p class="login-subtitle">Sign in to your admin account to continue.</p>

      <!-- Error / Success alert -->
      @if (session('success') || session('error') || $errors->any())
        <div class="alert show" id="loginAlert" style="display: flex; @if(session('success')) border-left-color: #4ecdc4; background: rgba(78,205,196,0.08); color: #4ecdc4; @endif">
          @if (session('success'))
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4ecdc4" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span id="alertMsg">{{ session('success') }}</span>
          @elseif (session('error'))
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="alertMsg">{{ session('error') }}</span>
          @elseif ($errors->any())
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="alertMsg">{{ $errors->first() }}</span>
          @endif
        </div>
      @else
        <div class="alert" id="loginAlert">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="alertMsg">Invalid email or password. Please try again.</span>
        </div>
      @endif

      <form id="loginForm" action="{{ route('admin.login.verify') }}" method="POST" novalidate>
        @csrf
        <!-- Email -->
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 8l10 6 10-6"/></svg>
            </span>
            <input class="form-input" type="email" id="email" name="email" placeholder="admin@eznirman.com" autocomplete="email" value="{{ old('email') }}" />
          </div>
          <div class="error-msg" id="emailError">Please enter a valid email address.</div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input class="form-input" type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" />
            <button type="button" class="toggle-pw" id="togglePw" aria-label="Toggle password visibility">
              <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="error-msg" id="passwordError">Password is required.</div>
        </div>

        <!-- Remember + Forgot -->
        <div class="form-meta">
          <label class="remember">
            <input type="checkbox" id="remember" name="remember" />
            Remember me
          </label>
          <!-- <a href="#" class="forgot-link">Forgot password?</a> -->
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-login" id="loginBtn">
          <span class="btn-text">Sign In to Admin</span>
          <div class="spinner"></div>
        </button>
      </form>

      <div class="login-footer">
        Not an admin? <a href="{{ route('home') }}">Back to home</a>
      </div>
    </div>

    <div class="version-badge">v2.4.1 — Secure</div>
  </div>

</div>

<script>
  const form     = document.getElementById('loginForm');
  const emailEl  = document.getElementById('email');
  const passEl   = document.getElementById('password');
  const btn      = document.getElementById('loginBtn');
  const alert    = document.getElementById('loginAlert');
  const alertMsg = document.getElementById('alertMsg');
  const togglePw = document.getElementById('togglePw');
  const eyeIcon  = document.getElementById('eyeIcon');

  // Password toggle
  togglePw.addEventListener('click', () => {
    const isText = passEl.type === 'text';
    passEl.type = isText ? 'password' : 'text';
    eyeIcon.innerHTML = isText
      ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
      : '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
  });

  function validateEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

  function setError(input, msgEl, show) {
    if (show) {
      input.classList.add('error');
      msgEl.classList.add('show');
    } else {
      input.classList.remove('error');
      msgEl.classList.remove('show');
    }
  }

  // Live clear on input
  emailEl.addEventListener('input', () => setError(emailEl, document.getElementById('emailError'), false));
  passEl.addEventListener('input',  () => setError(passEl,  document.getElementById('passwordError'), false));

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    if (alert) {
      alert.classList.remove('show');
      alert.style.display = 'none'; // reset default layout styling if we customized display
    }

    const email = emailEl.value.trim();
    const pass  = passEl.value;

    let valid = true;
    if (!validateEmail(email)) { setError(emailEl, document.getElementById('emailError'), true); valid = false; }
    if (!pass) { setError(passEl, document.getElementById('passwordError'), true); valid = false; }
    if (!valid) return;

    // Trigger button loading spinner
    btn.classList.add('loading');
    btn.disabled = true;

    // Submit form to Laravel backend
    form.submit();
  });

  // Auto-dismiss backend alerts after 5 seconds
  document.addEventListener('DOMContentLoaded', () => {
    if (alert && alert.classList.contains('show')) {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.4s ease';
        alert.style.opacity = '0';
        setTimeout(() => {
          alert.classList.remove('show');
          alert.style.opacity = '1';
        }, 400);
      }, 5000);
    }
  });
</script>
</body>
</html>