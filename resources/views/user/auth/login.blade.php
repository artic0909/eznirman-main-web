<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eznirman — User Login</title>
<link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
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
    --violet:         #7c6ff7;
    --violet-light:   #a59ef9;
    --violet-dim:     rgba(124,111,247,0.12);
    --violet-border:  rgba(124,111,247,0.3);
    --concrete:       #8fa0b0;
    --concrete-light: #c8d5df;
    --white:          #f4f6f8;
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

  body::before {
    content: '';
    position: fixed; inset: 0;
    background-image: repeating-linear-gradient(
      -55deg,
      transparent,
      transparent 40px,
      rgba(124,111,247,0.025) 40px,
      rgba(124,111,247,0.025) 41px
    );
    pointer-events: none; z-index: 0;
  }

  .blob { position: fixed; border-radius: 50%; filter: blur(150px); pointer-events: none; z-index: 0; }
  .blob-1 { width: 550px; height: 550px; background: var(--violet); opacity: 0.09; bottom: -150px; right: -150px; }
  .blob-2 { width: 300px; height: 300px; background: var(--amber); opacity: 0.07; top: -80px; left: 20%; }

  /* ── Faded Background Logo ── */
  .faded-bg-logo {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50vw;
    max-width: 600px;
    opacity: 0.04;
    pointer-events: none;
    z-index: 0;
    filter: grayscale(100%);
  }

  /* ── ROOT LAYOUT ── */
  .page {
    position: relative;
    z-index: 1;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* ── TOP NAV BAR ── */
  .topbar {
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 48px;
    border-bottom: 1px solid var(--steel-border);
    flex-shrink: 0;
    background: rgba(26,35,50,0.6);
    backdrop-filter: blur(8px);
    position: sticky;
    top: 0;
    z-index: 100;
  }

  .topbar-left { display: flex; align-items: center; gap: 32px; }

  .brand-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; flex-shrink: 0; }
  .logo-text { display: flex; align-items: baseline; gap: 1px; }
  .logo-ez     { font-family: 'Bebas Neue', sans-serif; font-size: 24px; color: var(--amber); letter-spacing: 2px; }
  .logo-nirman { font-family: 'Bebas Neue', sans-serif; font-size: 24px; color: var(--white); letter-spacing: 2px; }

  .step-bar {
    display: flex;
    align-items: center;
    gap: 0;
  }
  .step { display: flex; align-items: center; gap: 8px; }
  .step-num {
    width: 24px; height: 24px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'DM Mono', monospace; font-size: 10px;
    border: 1px solid var(--steel-border); color: var(--concrete);
    transition: all 0.3s; flex-shrink: 0;
  }
  .step.active .step-num { background: var(--violet); border-color: var(--violet); color: var(--white); }
  .step.done  .step-num { background: rgba(124,111,247,0.15); border-color: var(--violet-border); color: var(--violet-light); }
  .step-label {
    font-family: 'DM Mono', monospace;
    font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete);
  }
  .step.active .step-label { color: var(--violet-light); }
  .step-line { width: 28px; height: 1px; background: var(--steel-border); margin: 0 8px; flex-shrink: 0; }

  .portal-pill {
    display: flex; align-items: center; gap: 7px;
    background: var(--violet-dim);
    border: 1px solid var(--violet-border);
    padding: 5px 12px;
    font-family: 'DM Mono', monospace;
    font-size: 9px; letter-spacing: 3px; text-transform: uppercase;
    color: var(--violet-light);
    white-space: nowrap;
    flex-shrink: 0;
  }
  .portal-pill::before {
    content:''; width:5px; height:5px; border-radius:50%;
    background:var(--violet-light); animation: pulse 2s infinite; flex-shrink: 0;
  }
  @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

  /* ── CONTENT SPLIT ── */
  .content-split {
    flex: 1;
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    min-height: 0;
  }

  /* ── LEFT — Form side ── */
  .form-side {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 48px 64px;
    overflow-y: auto;
  }

  .form-inner {
    max-width: 420px;
    width: 100%;
    animation: fadeUp 0.65s ease both;
  }
  @keyframes fadeUp { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }

  .form-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 48px; letter-spacing: 1px; line-height: 1.05;
    margin-bottom: 8px;
  }
  .form-title .violet { color: var(--violet-light); }
  .form-sub { font-size: 13px; color: var(--concrete); margin-bottom: 28px; line-height: 1.5; }

  .auth-tabs {
    display: grid; grid-template-columns: 1fr 1fr;
    border: 1px solid var(--steel-border);
    margin-bottom: 22px; overflow: hidden;
  }
  .tab-btn {
    background: transparent; border: none;
    padding: 11px 0;
    font-family: 'DM Mono', monospace;
    font-size: 10px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--concrete); cursor: pointer;
    transition: background 0.2s, color 0.2s;
    display: flex; align-items: center; justify-content: center; gap: 7px;
  }
  .tab-btn:first-child { border-right: 1px solid var(--steel-border); }
  .tab-btn.active { background: var(--violet-dim); color: var(--violet-light); }

  .tab-panel { display: none; }
  .tab-panel.active { display: block; }

  .form-group { margin-bottom: 16px; }
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
  .input-wrap:focus-within .input-icon { color: var(--violet-light); }

  .form-input {
    width: 100%; background: var(--steel-mid);
    border: 1px solid var(--steel-border);
    color: var(--white); font-family: 'DM Sans', sans-serif;
    font-size: 14px; padding: 12px 16px 12px 40px;
    outline: none; transition: border-color 0.25s, background 0.2s;
    -webkit-appearance: none; border-radius: 0;
  }
  .form-input::placeholder { color: var(--concrete); opacity: 0.5; }
  .form-input:focus { border-color: var(--violet); background: rgba(36,48,68,0.9); }
  .form-input.error { border-color: var(--danger); }

  .phone-wrap { display: flex; }
  .country-code {
    flex-shrink: 0;
    background: rgba(124,111,247,0.08);
    border: 1px solid var(--steel-border); border-right: none;
    padding: 12px 12px;
    font-family: 'DM Mono', monospace;
    font-size: 13px; color: var(--violet-light);
    display: flex; align-items: center; gap: 6px; white-space: nowrap;
  }
  .phone-wrap .form-input { border-left: none; padding-left: 14px; }
  .phone-wrap:focus-within .country-code { border-color: var(--violet); background: rgba(124,111,247,0.12); }
  .phone-wrap:focus-within .form-input   { border-color: var(--violet); }

  .toggle-pw {
    position: absolute; right: 12px;
    background: none; border: none; color: var(--concrete);
    cursor: pointer; display: flex; padding: 4px; transition: color 0.2s;
  }
  .toggle-pw:hover { color: var(--violet-light); }

  .error-msg { display:none; font-size:11px; color:var(--danger); margin-top:5px; font-family:'DM Mono',monospace; letter-spacing:.5px; }
  .error-msg.show { display:block; }

  .strength-bar { display: flex; gap: 4px; margin-top: 8px; }
  .strength-seg { flex: 1; height: 3px; background: var(--steel-border); transition: background 0.3s; }

  .form-meta {
    display: flex; align-items: center; justify-content: space-between;
    margin: 16px 0 20px;
  }
  .remember {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--concrete); cursor: pointer; user-select: none;
  }
  .remember input[type="checkbox"] {
    width: 16px; height: 16px; appearance: none; -webkit-appearance: none;
    background: var(--steel-mid); border: 1px solid var(--steel-border);
    cursor: pointer; position: relative; flex-shrink: 0; transition: background .2s, border-color .2s;
  }
  .remember input:checked { background: var(--violet); border-color: var(--violet); }
  .remember input:checked::after {
    content: ''; position: absolute; top: 2px; left: 5px;
    width: 4px; height: 8px; border: 2px solid #fff; border-top: none; border-left: none; transform: rotate(45deg);
  }
  .forgot-link {
    font-size: 12px; font-family: 'DM Mono', monospace;
    letter-spacing: 1px; text-transform: uppercase;
    color: var(--concrete); text-decoration: none; transition: color .2s;
  }
  .forgot-link:hover { color: var(--violet-light); }

  .alert {
    display: none; align-items: center; gap: 10px;
    padding: 11px 14px; margin-bottom: 16px;
    border-left: 3px solid var(--danger);
    background: rgba(224,92,92,0.08);
    font-size: 13px; color: #f08080;
  }
  .alert.show { display: flex; }

  .btn-login {
    width: 100%; font-family: 'DM Sans', sans-serif;
    font-size: 14px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;
    color: var(--white); background: var(--violet);
    border: none; padding: 14px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background 0.2s, transform 0.15s;
  }
  .btn-login:hover { background: var(--violet-light); color: var(--steel); transform: translateY(-1px); }
  .btn-login:active { transform: translateY(0); }
  .btn-login:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
  .spinner { display:none; width:16px; height:16px; border:2px solid rgba(255,255,255,0.3); border-top-color:#fff; border-radius:50%; animation:spin .7s linear infinite; }
  @keyframes spin { to{transform:rotate(360deg);} }
  .btn-login.loading .btn-text { display:none; }
  .btn-login.loading .spinner { display:block; }

  .or-divider { display:flex; align-items:center; gap:12px; margin:20px 0; }
  .or-divider::before,.or-divider::after { content:''; flex:1; height:1px; background:var(--steel-border); }
  .or-divider span { font-family:'DM Mono',monospace; font-size:9px; letter-spacing:2px; text-transform:uppercase; color:var(--concrete); opacity:.5; }

  .sso-btn {
    width:100%; background:transparent; border:1px solid var(--steel-border);
    padding:11px; font-family:'DM Sans',sans-serif; font-size:13px; font-weight:400;
    color:var(--concrete-light); cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:10px;
    transition:border-color .2s, color .2s;
  }
  .sso-btn:hover { border-color:var(--violet-border); color:var(--violet-light); }

  .form-footer { margin-top:18px; text-align:center; font-size:12px; color:var(--concrete); }
  .form-footer a { color:var(--violet-light); text-decoration:none; }
  .form-footer a:hover { text-decoration:underline; }

  /* ── BOTTOM BAR ── */
  .bottombar {
    height: 52px;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 48px;
    border-top: 1px solid var(--steel-border);
    flex-shrink: 0;
  }
  .bottom-links { display:flex; gap:24px; }
  .bottom-links a {
    font-family:'DM Mono',monospace; font-size:9px; letter-spacing:2px; text-transform:uppercase;
    color:var(--concrete); text-decoration:none; opacity:.5; transition:opacity .2s;
  }
  .bottom-links a:hover { opacity:1; }
  .secure-badge {
    display:flex; align-items:center; gap:6px;
    font-family:'DM Mono',monospace; font-size:9px; letter-spacing:2px; text-transform:uppercase;
    color:var(--concrete); opacity:.4;
  }

  /* ── RIGHT — Visual panel ── */
  .visual-side {
    background: linear-gradient(160deg, rgba(124,111,247,0.08) 0%, rgba(26,35,50,0.4) 60%, rgba(26,35,50,0) 100%);
    border-left: 1px solid rgba(124,111,247,0.1);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 48px 40px; position: relative; overflow: hidden;
  }

  .visual-side::before {
    content: 'EZ';
    position: absolute; bottom: -60px; right: -20px;
    font-family: 'Bebas Neue', sans-serif; font-size: 320px;
    color: rgba(124,111,247,0.04); letter-spacing: -8px; pointer-events: none; line-height: 1;
  }

  .visual-content { position: relative; z-index: 1; text-align: center; max-width: 320px; }

  .vis-icon-wrap {
    width: 88px; height: 88px; margin: 0 auto 28px;
    border: 1px solid var(--violet-border); background: var(--violet-dim);
    display: flex; align-items: center; justify-content: center; position: relative;
  }
  .vis-icon-wrap::before {
    content: ''; position: absolute; inset: -6px;
    border: 1px solid rgba(124,111,247,0.1);
  }
  .vis-icon-wrap svg { color: var(--violet-light); }

  .vis-title { font-family:'Bebas Neue',sans-serif; font-size:clamp(28px,3vw,40px); letter-spacing:1px; margin-bottom:10px; }
  .vis-title .v { color:var(--violet-light); }
  .vis-desc { font-size:13px; color:var(--concrete); line-height:1.7; margin-bottom:32px; }

  .vis-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:1px; background:rgba(124,111,247,0.1); width:100%; }
  .vis-stat { background:var(--steel); padding:16px 8px; text-align:center; }
  .vis-stat-num { font-family:'Bebas Neue',sans-serif; font-size:28px; color:var(--white); line-height:1; }
  .vis-stat-num.v { color:var(--violet-light); }
  .vis-stat-key { font-family:'DM Mono',monospace; font-size:8px; letter-spacing:2px; text-transform:uppercase; color:var(--concrete); margin-top:3px; }

  .role-cards { display:flex; flex-direction:column; gap:8px; margin-top:24px; width:100%; }
  .role-card {
    display:flex; align-items:center; gap:12px;
    background:rgba(26,35,50,0.6); border:1px solid var(--steel-border);
    padding:11px 14px; transition:border-color .2s;
  }
  .role-card:hover { border-color:var(--violet-border); }
  .role-card-icon {
    width:32px; height:32px; background:var(--violet-dim);
    border:1px solid var(--violet-border);
    display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--violet-light);
  }
  .role-card-name { font-size:12px; font-weight:500; color:var(--white); }
  .role-card-desc { font-size:11px; color:var(--concrete); margin-top:1px; }
  .role-card-arrow { margin-left:auto; color:var(--concrete); font-size:14px; }

  /* ── RESPONSIVE ── */
  @media (max-width: 1100px) {
    .content-split { grid-template-columns: 1fr; }
    .visual-side { display: none; }
    .form-side { padding: 40px 48px; align-items: center; }
    .form-inner { max-width: 480px; }
  }

  @media (max-width: 768px) {
    .topbar { padding: 0 24px; gap: 12px; }
    .step-label { display: none; }
    .form-side { padding: 32px 24px; }
    .form-inner { max-width: 100%; }
    .bottombar { padding: 0 24px; }
    .form-title { font-size: 40px; }
  }

  @media (max-width: 520px) {
    .topbar { padding: 0 16px; height: 56px; }
    .portal-pill { display: none; }
    .form-side { padding: 24px 16px; }
    .bottombar { padding: 0 16px; }
    .form-title { font-size: 34px; }
    .form-meta { flex-direction: column; align-items: flex-start; gap: 12px; }
    .bottom-links { gap: 16px; }
    .step-line { width: 16px; margin: 0 4px; }
  }

  @media (max-width: 360px) {
    .topbar { height: 52px; }
    .form-title { font-size: 30px; }
  }
</style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<img src="{{ asset('logo.png') }}" class="faded-bg-logo" alt="">

<div class="page">

  <!-- ── TOP BAR ── -->
  <nav class="topbar">
    <div class="topbar-left">
      <a href="{{ route('home') }}" class="brand-logo">
        <img src="{{ asset('logo.png') }}" alt="EZNIRMAN Logo" style="height: 24px;">
        <div class="logo-text">
          <span class="logo-ez">EZ</span><span class="logo-nirman">NIRMAN</span>
        </div>
      </a>

      <div class="step-bar">
        <div class="step active" id="step1">
          <div class="step-num">1</div>
          <div class="step-label">Identity</div>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step2">
          <div class="step-num">2</div>
          <div class="step-label">Verify</div>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step3">
          <div class="step-num">3</div>
          <div class="step-label">Access</div>
        </div>
      </div>
    </div>

    <div class="portal-pill">User Portal</div>
  </nav>

  <!-- ── MAIN CONTENT ── -->
  <div class="content-split">

    <!-- FORM SIDE -->
    <div class="form-side">
      <div class="form-inner">

        <h1 class="form-title">Sign In <span class="violet">To</span><br>Your Account</h1>
        <p class="form-sub">Select your preferred login method below.</p>

        @if (session('status') || $errors->any())
        <div class="alert show" id="loginAlert" style="display: flex;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="alertMsg">
            @if (session('status'))
                {{ session('status') }}
            @elseif ($errors->any())
                {{ $errors->first() }}
            @endif
          </span>
        </div>
        @else
        <div class="alert" id="loginAlert">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="alertMsg">Invalid credentials. Please try again.</span>
        </div>
        @endif

        <div class="auth-tabs">
          <button class="tab-btn active" id="tabEmail" type="button">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 8l10 6 10-6"/></svg>
            Email
          </button>
          <button class="tab-btn" id="tabMobile" type="button">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
            Staff Code
          </button>
        </div>

        <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
          @csrf

          <div class="tab-panel active" id="panelEmail">
            <div class="form-group">
              <label class="form-label" for="email">Email Address</label>
              <div class="input-wrap">
                <span class="input-icon">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 8l10 6 10-6"/></svg>
                </span>
                <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email" />
              </div>
              <div class="error-msg" id="emailError">Enter a valid email address.</div>
            </div>
          </div>

          <div class="tab-panel" id="panelMobile">
            <div class="form-group">
              <label class="form-label" for="staffcode">Staff Code</label>
              <div class="input-wrap">
                <span class="input-icon">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                </span>
                <input class="form-input" type="text" id="staffcode" placeholder="e.g. 11074" maxlength="20" autocomplete="off" style="text-transform:uppercase;letter-spacing:2px;" />
              </div>
              <div class="error-msg" id="mobileError">Enter a valid staff code (e.g. 11074).</div>
            </div>
          </div>

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
            <div class="strength-bar" id="strengthBar">
              <div class="strength-seg" id="s1"></div>
              <div class="strength-seg" id="s2"></div>
              <div class="strength-seg" id="s3"></div>
              <div class="strength-seg" id="s4"></div>
            </div>
            <div class="error-msg" id="passwordError">Password is required.</div>
          </div>

          <div class="form-meta">
            <label class="remember">
              <input type="checkbox" id="remember" name="remember" />
              Stay signed in
            </label>
          </div>

          <button type="submit" class="btn-login" id="loginBtn">
            <span class="btn-text">Continue to Dashboard</span>
            <div class="spinner"></div>
          </button>
        </form>

        <div class="form-footer">
          Admin? <a href="{{ route('admin.login') }}">Admin Login</a> &nbsp;·&nbsp;
          Accountant? <a href="{{ route('account.login') }}">Finance Login</a>
        </div>

      </div>
    </div>

    <!-- VISUAL SIDE -->
    <div class="visual-side">
      <div class="visual-content">
        <div class="vis-icon-wrap">
          <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
        </div>

        <h2 class="vis-title">Your Work.<br><span class="v">One Place.</span></h2>
        <p class="vis-desc">Access your assigned projects, daily tasks, site reports, and team updates — all from your personal dashboard.</p>

        <div class="vis-stats">
          <div class="vis-stat">
            <div class="vis-stat-num v">24</div>
            <div class="vis-stat-key">Live Sites</div>
          </div>
          <div class="vis-stat">
            <div class="vis-stat-num">430</div>
            <div class="vis-stat-key">Active Users</div>
          </div>
          <div class="vis-stat">
            <div class="vis-stat-num v">99%</div>
            <div class="vis-stat-key">Uptime</div>
          </div>
        </div>

        <div class="role-cards">
          <div class="role-card">
            <div class="role-card-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            </div>
            <div>
              <div class="role-card-name">Site Supervisor</div>
              <div class="role-card-desc">Daily logs, worker attendance, quality checks</div>
            </div>
            <span class="role-card-arrow">›</span>
          </div>
          <div class="role-card">
            <div class="role-card-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            </div>
            <div>
              <div class="role-card-name">Project Manager</div>
              <div class="role-card-desc">Schedules, milestones, resource allocation</div>
            </div>
            <span class="role-card-arrow">›</span>
          </div>
          <div class="role-card">
            <div class="role-card-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
              <div class="role-card-name">Field Engineer</div>
              <div class="role-card-desc">Drawings, BOQ, inspection reports</div>
            </div>
            <span class="role-card-arrow">›</span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- ── BOTTOM BAR ── -->
  <footer class="bottombar">
    <div class="bottom-links">
      <a href="#">Privacy</a>
      <a href="#">Terms</a>
      <a href="#">Support</a>
    </div>
    <div class="secure-badge">
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      256-bit encrypted
    </div>
  </footer>

</div>

<script>
  const tabEmail  = document.getElementById('tabEmail');
  const tabMobile = document.getElementById('tabMobile');
  const panelEmail  = document.getElementById('panelEmail');
  const panelMobile = document.getElementById('panelMobile');

  tabEmail.addEventListener('click', () => {
    tabEmail.classList.add('active'); tabMobile.classList.remove('active');
    panelEmail.classList.add('active'); panelMobile.classList.remove('active');
  });
  tabMobile.addEventListener('click', () => {
    tabMobile.classList.add('active'); tabEmail.classList.remove('active');
    panelMobile.classList.add('active'); panelEmail.classList.remove('active');
  });

  const passEl  = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');
  document.getElementById('togglePw').addEventListener('click', () => {
    const show = passEl.type === 'password';
    passEl.type = show ? 'text' : 'password';
    eyeIcon.innerHTML = show
      ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
      : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  });

  passEl.addEventListener('input', () => {
    const v = passEl.value;
    const segs = ['s1','s2','s3','s4'].map(id => document.getElementById(id));
    const colors = ['#e05c5c','#e8a020','#e8a020','#7c6ff7'];
    let score = 0;
    if (v.length >= 6) score++;
    if (v.length >= 10) score++;
    if (/[A-Z]/.test(v) && /[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    segs.forEach((s, i) => { s.style.background = i < score ? colors[Math.min(score-1,3)] : 'var(--steel-border)'; });
  });

  ['email','staffcode','password'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', () => {
      const errMap = { email:'emailError', staffcode:'mobileError', password:'passwordError' };
      document.getElementById(errMap[id])?.classList.remove('show');
      el.classList.remove('error');
    });
  });

  function setStep(n) {
    for (let i = 1; i <= 3; i++) {
      document.getElementById('step'+i).className = 'step' + (i < n ? ' done' : i === n ? ' active' : '');
    }
  }

  const form     = document.getElementById('loginForm');
  const btn      = document.getElementById('loginBtn');
  const alertEl  = document.getElementById('loginAlert');
  const alertMsg = document.getElementById('alertMsg');

  form.addEventListener('submit', async e => {
    e.preventDefault();
    if (alertEl) alertEl.classList.remove('show');

    const isMobile = tabMobile.classList.contains('active');
    const emailVal  = document.getElementById('email').value.trim();
    const pass      = passEl.value;
    let ok = true;

    if (!isMobile && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
      document.getElementById('emailError').classList.add('show');
      document.getElementById('email').classList.add('error'); ok = false;
    }
    const staffVal = document.getElementById('staffcode') ? document.getElementById('staffcode').value.trim() : '';
    if (isMobile && (!staffVal || staffVal.length < 2)) {
      document.getElementById('mobileError').classList.add('show');
      document.getElementById('staffcode').classList.add('error'); ok = false;
    }
    if (!pass) {
      document.getElementById('passwordError').classList.add('show');
      passEl.classList.add('error'); ok = false;
    }
    if (!ok) return;

    setStep(2);
    btn.classList.add('loading'); btn.disabled = true;

    const emailInput = document.getElementById('email');
    const staffcodeInput = document.getElementById('staffcode');
    if (isMobile) {
      staffcodeInput.name = 'email';
      emailInput.removeAttribute('name');
    } else {
      emailInput.name = 'email';
      staffcodeInput.removeAttribute('name');
    }

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