<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eznirman — Construction Management System</title>
<link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --steel: #1a2332;
    --steel-mid: #243044;
    --steel-light: #2e3d54;
    --amber: #e8a020;
    --amber-pale: #f5c85a;
    --amber-dim: #7a5210;
    --concrete: #8fa0b0;
    --concrete-light: #c8d5df;
    --white: #f4f6f8;
    --accent-line: rgba(232, 160, 32, 0.3);
  }

  html { scroll-behavior: smooth; }

  body {
    background: var(--steel);
    color: var(--white);
    font-family: 'DM Sans', sans-serif;
    font-weight: 300;
    overflow-x: hidden;
    min-height: 100vh;
  }

  /* ── Grid background ── */
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image:
      linear-gradient(rgba(232,160,32,0.04) 1px, transparent 1px),
      linear-gradient(90deg, rgba(232,160,32,0.04) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
    z-index: 0;
  }

  /* ── Glow blobs ── */
  .blob {
    position: fixed;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.15;
    pointer-events: none;
    z-index: 0;
  }
  .blob-1 { width: 600px; height: 600px; background: var(--amber); top: -200px; right: -100px; }
  .blob-2 { width: 400px; height: 400px; background: #4a90d9; bottom: 100px; left: -150px; opacity: 0.1; }

  /* ── Nav ── */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 56px;
    height: 72px;
    border-bottom: 1px solid rgba(232,160,32,0.12);
    background: rgba(26,35,50,0.85);
    backdrop-filter: blur(16px);
  }

  .logo {
    display: flex;
    align-items: baseline;
    gap: 2px;
  }
  .logo-ez {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 28px;
    color: var(--amber);
    letter-spacing: 2px;
  }
  .logo-nirman {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 28px;
    color: var(--white);
    letter-spacing: 2px;
  }
  .logo-tag {
    font-family: 'DM Mono', monospace;
    font-size: 9px;
    color: var(--concrete);
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-left: 12px;
    border-left: 1px solid var(--accent-line);
    padding-left: 12px;
    line-height: 1;
    align-self: center;
  }

  .nav-links {
    display: flex;
    gap: 36px;
    list-style: none;
  }
  .nav-links a {
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    letter-spacing: 1px;
    color: var(--concrete-light);
    text-transform: uppercase;
    transition: color 0.2s;
  }
  .nav-links a:hover { color: var(--amber); }

  .nav-cta {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .btn-ghost {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--concrete-light);
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 8px 0;
    transition: color 0.2s;
    text-decoration: none;
  }
  .btn-ghost:hover { color: var(--white); }

  .btn-primary {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--steel);
    background: var(--amber);
    border: none;
    padding: 10px 24px;
    cursor: pointer;
    clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
    transition: background 0.2s, transform 0.15s;
    text-decoration: none;
  }
  .btn-primary:hover { background: var(--amber-pale); transform: translateY(-1px); }

  /* ── Hero ── */
  .hero {
    position: relative;
    z-index: 1;
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    padding: 120px 56px 80px;
    gap: 60px;
  }

  .hero-left { max-width: 620px; }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--amber);
    border: 1px solid var(--accent-line);
    padding: 6px 14px;
    margin-bottom: 32px;
    animation: fadeUp 0.8s ease both;
  }
  .hero-badge::before {
    content: '';
    display: inline-block;
    width: 6px; height: 6px;
    background: var(--amber);
    border-radius: 50%;
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
  }

  .hero-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(56px, 7vw, 96px);
    line-height: 0.9;
    letter-spacing: 2px;
    margin-bottom: 28px;
    animation: fadeUp 0.8s 0.1s ease both;
  }

  .hero-title .accent { color: var(--amber); }
  .hero-title .line-2 { display: block; color: var(--concrete-light); }

  .hero-sub {
    font-size: 16px;
    font-weight: 300;
    line-height: 1.7;
    color: var(--concrete);
    max-width: 460px;
    margin-bottom: 48px;
    animation: fadeUp 0.8s 0.2s ease both;
  }

  .hero-actions {
    display: flex;
    align-items: center;
    gap: 24px;
    animation: fadeUp 0.8s 0.3s ease both;
  }

  .btn-large {
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--steel);
    background: var(--amber);
    border: none;
    padding: 16px 36px;
    cursor: pointer;
    clip-path: polygon(10px 0%, 100% 0%, calc(100% - 10px) 100%, 0% 100%);
    transition: background 0.2s, transform 0.15s;
    text-decoration: none;
    display: inline-block;
  }
  .btn-large:hover { background: var(--amber-pale); transform: translateY(-2px); }

  .btn-outline {
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    font-weight: 400;
    letter-spacing: 1px;
    color: var(--concrete-light);
    background: transparent;
    border: 1px solid rgba(200,213,223,0.25);
    padding: 15px 28px;
    cursor: pointer;
    transition: border-color 0.2s, color 0.2s;
    text-decoration: none;
    display: inline-block;
  }
  .btn-outline:hover { border-color: var(--concrete-light); color: var(--white); }

  /* ── Hero Stats ── */
  .hero-stats {
    display: flex;
    gap: 40px;
    margin-top: 64px;
    padding-top: 40px;
    border-top: 1px solid rgba(232,160,32,0.12);
    animation: fadeUp 0.8s 0.4s ease both;
  }
  .stat-item {}
  .stat-number {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 42px;
    color: var(--white);
    letter-spacing: 1px;
    line-height: 1;
  }
  .stat-number span { color: var(--amber); }
  .stat-label {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--concrete);
    margin-top: 4px;
  }

  /* ── Hero Right: Dashboard Preview ── */
  .hero-right {
    position: relative;
    animation: fadeUp 0.8s 0.2s ease both;
  }

  .dashboard-card {
    background: rgba(36,48,68,0.8);
    border: 1px solid rgba(232,160,32,0.15);
    backdrop-filter: blur(8px);
    padding: 24px;
    border-radius: 2px;
  }

  .dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .dash-title {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--concrete);
  }
  .dash-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--amber); animation: pulse 2s infinite; }

  .project-list { display: flex; flex-direction: column; gap: 12px; }

  .project-row {
    display: grid;
    grid-template-columns: 1fr auto auto;
    align-items: center;
    gap: 16px;
    padding: 14px 16px;
    background: rgba(26,35,50,0.6);
    border-left: 2px solid transparent;
    transition: border-color 0.2s;
  }
  .project-row:hover { border-left-color: var(--amber); }
  .project-row:nth-child(2) { border-left-color: var(--amber); }

  .proj-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--white);
    margin-bottom: 4px;
  }
  .proj-location {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 1px;
    color: var(--concrete);
  }

  .proj-progress {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 6px;
  }
  .progress-pct {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    color: var(--amber);
  }
  .progress-bar {
    width: 80px;
    height: 3px;
    background: rgba(255,255,255,0.08);
    border-radius: 2px;
    overflow: hidden;
  }
  .progress-fill {
    height: 100%;
    background: var(--amber);
    border-radius: 2px;
  }

  .proj-status {
    font-family: 'DM Mono', monospace;
    font-size: 9px;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 1px;
  }
  .status-active { color: #4ecdc4; background: rgba(78,205,196,0.1); border: 1px solid rgba(78,205,196,0.25); }
  .status-hold { color: var(--amber); background: rgba(232,160,32,0.1); border: 1px solid var(--accent-line); }
  .status-review { color: #a78bfa; background: rgba(167,139,250,0.1); border: 1px solid rgba(167,139,250,0.25); }

  /* Mini chart */
  .mini-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 16px;
  }
  .metric-box {
    background: rgba(26,35,50,0.6);
    padding: 14px;
    text-align: center;
  }
  .metric-val {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 28px;
    color: var(--white);
    line-height: 1;
  }
  .metric-val.amber { color: var(--amber); }
  .metric-key {
    font-family: 'DM Mono', monospace;
    font-size: 9px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--concrete);
    margin-top: 4px;
  }

  /* Floating tag */
  .float-tag {
    position: absolute;
    top: -20px;
    right: -20px;
    background: var(--amber);
    color: var(--steel);
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 8px 16px;
    font-weight: 500;
    clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
  }

  /* ── Features ── */
  .features {
    position: relative;
    z-index: 1;
    padding: 120px 56px;
    border-top: 1px solid rgba(232,160,32,0.08);
  }

  .section-label {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--amber);
    margin-bottom: 16px;
  }
  .section-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(36px, 5vw, 60px);
    letter-spacing: 1px;
    margin-bottom: 60px;
    color: var(--white);
  }
  .section-title .muted { color: var(--steel-light); -webkit-text-stroke: 1px var(--concrete); }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: rgba(232,160,32,0.08);
  }

  .feature-card {
    background: var(--steel);
    padding: 40px 32px;
    position: relative;
    overflow: hidden;
    transition: background 0.3s;
  }
  .feature-card:hover { background: var(--steel-mid); }
  .feature-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 0; height: 2px;
    background: var(--amber);
    transition: width 0.4s ease;
  }
  .feature-card:hover::after { width: 100%; }

  .feature-number {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 64px;
    color: rgba(232,160,32,0.08);
    line-height: 1;
    position: absolute;
    top: 16px; right: 20px;
    transition: color 0.3s;
  }
  .feature-card:hover .feature-number { color: rgba(232,160,32,0.15); }

  .feature-icon {
    width: 44px; height: 44px;
    border: 1px solid var(--accent-line);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    color: var(--amber);
  }

  .feature-name {
    font-family: 'DM Sans', sans-serif;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: var(--white);
    margin-bottom: 12px;
  }

  .feature-desc {
    font-size: 14px;
    line-height: 1.7;
    color: var(--concrete);
  }

  /* ── CTA ── */
  .cta-section {
    position: relative;
    z-index: 1;
    padding: 100px 56px;
    text-align: center;
    border-top: 1px solid rgba(232,160,32,0.08);
    overflow: hidden;
  }
  .cta-section::before {
    content: 'EZNIRMAN';
    font-family: 'Bebas Neue', sans-serif;
    font-size: 200px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: rgba(232,160,32,0.03);
    white-space: nowrap;
    pointer-events: none;
    letter-spacing: 10px;
  }

  .cta-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(40px, 6vw, 72px);
    letter-spacing: 2px;
    margin-bottom: 16px;
    color: var(--white);
  }
  .cta-sub {
    font-size: 16px;
    color: var(--concrete);
    margin-bottom: 48px;
    font-weight: 300;
  }
  .cta-buttons { display: flex; align-items: center; justify-content: center; gap: 20px; }

  /* ── Footer ── */
  footer {
    position: relative;
    z-index: 1;
    padding: 32px 56px;
    border-top: 1px solid rgba(232,160,32,0.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .footer-copy {
    font-family: 'DM Mono', monospace;
    font-size: 11px;
    letter-spacing: 1px;
    color: var(--concrete);
  }
  .footer-links { display: flex; gap: 32px; list-style: none; }
  .footer-links a {
    font-size: 12px;
    color: var(--concrete);
    text-decoration: none;
    transition: color 0.2s;
  }
  .footer-links a:hover { color: var(--amber); }

  /* ── Burger Button ── */
  .burger {
    display: none;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    width: 40px;
    height: 40px;
    background: transparent;
    border: 1px solid rgba(232,160,32,0.25);
    cursor: pointer;
    padding: 8px 9px;
    z-index: 200;
    flex-shrink: 0;
    transition: border-color 0.2s;
  }
  .burger:hover { border-color: var(--amber); }
  .burger span {
    display: block;
    width: 100%;
    height: 1.5px;
    background: var(--concrete-light);
    transition: transform 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.2s, background 0.2s;
    transform-origin: center;
  }
  .burger.open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); background: var(--amber); }
  .burger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
  .burger.open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); background: var(--amber); }

  /* ── Right Sidebar ── */
  .sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(10,15,25,0.6);
    z-index: 149;
    opacity: 0;
    transition: opacity 0.35s ease;
    backdrop-filter: blur(4px);
  }
  .sidebar-overlay.visible { opacity: 1; }

  .sidebar {
    position: fixed;
    top: 0; right: 0; bottom: 0;
    width: min(320px, 85vw);
    background: var(--steel-mid);
    border-left: 1px solid rgba(232,160,32,0.15);
    z-index: 150;
    transform: translateX(100%);
    transition: transform 0.4s cubic-bezier(0.4,0,0.2,1);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
  }
  .sidebar.open { transform: translateX(0); }

  .sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(232,160,32,0.1);
    min-height: 72px;
  }

  .sidebar-logo {
    display: flex;
    align-items: baseline;
    gap: 2px;
  }

  .sidebar-close {
    width: 36px; height: 36px;
    background: transparent;
    border: 1px solid rgba(232,160,32,0.2);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--concrete-light);
    transition: border-color 0.2s, color 0.2s;
    flex-shrink: 0;
  }
  .sidebar-close:hover { border-color: var(--amber); color: var(--amber); }

  .sidebar-nav {
    padding: 32px 24px;
    flex: 1;
    display: block;
    position: static;
    height: auto;
    border-bottom: none;
    background: transparent;
    backdrop-filter: none;
  }

  .sidebar-nav-label {
    display: block;
    font-family: 'DM Mono', monospace;
    font-size: 9px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--amber);
    margin-bottom: 16px;
    opacity: 0.7;
  }

  .sidebar-links {
    list-style: none;
    margin-bottom: 36px;
    display: block;
  }
  .sidebar-links li { border-bottom: 1px solid rgba(255,255,255,0.04); display: block; }
  .sidebar-links a {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    font-size: 15px;
    font-weight: 400;
    letter-spacing: 0.5px;
    color: var(--concrete-light);
    padding: 14px 0;
    transition: color 0.2s, padding-left 0.2s;
  }
  .sidebar-links a:hover { color: var(--white); padding-left: 6px; }
  .sidebar-links a .link-arrow {
    margin-left: auto;
    font-size: 12px;
    color: var(--concrete);
    transition: color 0.2s, transform 0.2s;
  }
  .sidebar-links a:hover .link-arrow { color: var(--amber); transform: translateX(3px); }

  .sidebar-divider {
    height: 1px;
    background: rgba(232,160,32,0.08);
    margin: 8px 0 28px;
  }

  .sidebar-actions {
    padding: 0 24px 32px;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .sidebar-btn-ghost {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--concrete-light);
    background: transparent;
    border: 1px solid rgba(200,213,223,0.2);
    padding: 13px 24px;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    transition: border-color 0.2s, color 0.2s;
    display: block;
  }
  .sidebar-btn-ghost:hover { border-color: var(--concrete-light); color: var(--white); }

  .sidebar-btn-primary {
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--steel);
    background: var(--amber);
    border: none;
    padding: 14px 24px;
    cursor: pointer;
    clip-path: polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%);
    transition: background 0.2s;
    text-decoration: none;
    text-align: center;
    display: block;
  }
  .sidebar-btn-primary:hover { background: var(--amber-pale); }

  .sidebar-footer {
    padding: 20px 24px;
    border-top: 1px solid rgba(232,160,32,0.08);
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 1px;
    color: var(--concrete);
  }

  /* ── Animations ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* ── Responsive ── */
  @media (max-width: 1024px) {
    .hero { grid-template-columns: 1fr; min-height: auto; padding-top: 140px; }
    .hero-right { display: none; }
    .features-grid { grid-template-columns: repeat(2, 1fr); }
    nav { padding: 0 24px; }
    .nav-links { display: none; }
    .nav-cta { display: none; }
    .burger { display: flex; }
    .hero { padding: 120px 24px 80px; }
    .features { padding: 80px 24px; }
    .cta-section { padding: 80px 24px; }
    footer { padding: 24px; flex-direction: column; gap: 16px; text-align: center; }
  }

  @media (max-width: 640px) {
    .features-grid { grid-template-columns: 1fr; }
    .hero-stats { flex-wrap: wrap; gap: 24px; }
    .hero-actions { flex-direction: column; align-items: flex-start; gap: 14px; }
    .cta-buttons { flex-direction: column; align-items: center; }
    .logo-tag { display: none; }
  }
</style>
</head>
<body>

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>

<!-- SIDEBAR OVERLAY -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- RIGHT SIDEBAR -->
<aside class="sidebar" id="sidebar" aria-label="Navigation menu" aria-hidden="true">
  <div class="sidebar-header">
    <div class="sidebar-logo">
      <span class="logo-ez">EZ</span><span class="logo-nirman">NIRMAN</span>
    </div>
    <button class="sidebar-close" id="sidebarClose" aria-label="Close menu">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>
  <nav class="sidebar-nav">
    <div class="sidebar-nav-label">Navigation</div>
    <ul class="sidebar-links">
      <li><a href="/login"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>Staff Login<span class="link-arrow">›</span></a></li>
      <li><a href="{{route('account.login')}}"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>Accounts Dashboard<span class="link-arrow">›</span></a></li>
      <li><a href="{{route('admin.login')}}"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Administrator Dashboard<span class="link-arrow">›</span></a></li>
    </ul>
    <div class="sidebar-divider"></div>
    <div class="sidebar-nav-label">Support</div>
    <ul class="sidebar-links">
      <li><a href="tel:+916292237205"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>Call Support<span class="link-arrow">›</span></a></li>
      <li><a href="https://wa.me/+916292237205?text=Hey%20*Saklin*,%20I%20need%20help%20in%20EZ%20Nirman%20Software"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Chat Support<span class="link-arrow">›</span></a></li>
    </ul>
  </nav>
  <div class="sidebar-actions">
    <a href="#" class="sidebar-btn-primary">Get App</a>
  </div>
  <div class="sidebar-footer">© 2026 Eznirman.com — Construction OS</div>
</aside>

<!-- NAV -->
<nav>
  <div class="logo">
    <span class="logo-ez">EZ</span><span class="logo-nirman">NIRMAN</span>
    <span class="logo-tag">Construction OS</span>
  </div>
  <ul class="nav-links">
    <li><a href="{{route('admin.login')}}">Admin Access</a></li>
    <li><a href="{{route('account.login')}}">Accounts</a></li>
    <li><a href="/login">Staff</a></li>
  </ul>
  <div class="nav-cta">
    <a href="/login" class="btn-ghost">Supervisor</a>
    <a href="#" class="btn-primary">Get App</a>
  </div>
  <button class="burger" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-badge">v2.4 — Now Live</div>
    <h1 class="hero-title">
      Build <span class="accent">Smarter.</span>
      <span class="line-2">Deliver Faster.</span>
    </h1>
    <p class="hero-sub">
      Eznirman centralises your entire construction workflow — from site scheduling and material procurement to contractor coordination and financial tracking — in one command centre.
    </p>
    <div class="hero-actions">
      <a href="#" class="btn-large">Get Application</a>
      <a href="#features" class="btn-outline">Explore Features</a>
    </div>
    <div class="hero-stats">
      <div class="stat-item">
        <div class="stat-number">14<span>K</span></div>
        <div class="stat-label">Projects Managed</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">98<span>%</span></div>
        <div class="stat-label">On-time Delivery</div>
      </div>
      <div class="stat-item">
        <div class="stat-number">3<span>X</span></div>
        <div class="stat-label">Faster Reporting</div>
      </div>
    </div>
  </div>

  <div class="hero-right">
    <div style="position: relative;">
      <span class="float-tag">Live Dashboard</span>
      <div class="dashboard-card">
        <div class="dash-header">
          <span class="dash-title">Active Monitoring — System 2026</span>
          <div class="dash-dot"></div>
        </div>
        <div class="project-list">
          <div class="project-row">
            <div>
              <div class="proj-name">Machinery Tracking</div>
              <div class="proj-location">Site Wise ↗ Data flow</div>
            </div>
            <div class="proj-progress">
              <span class="progress-pct">100%</span>
              <div class="progress-bar"><div class="progress-fill" style="width:100%"></div></div>
            </div>
            <span class="proj-status status-active">Active</span>
          </div>
          <div class="project-row">
            <div>
              <div class="proj-name">Material Purchase</div>
              <div class="proj-location">In Site ↗ Data flow</div>
            </div>
            <div class="proj-progress">
              <span class="progress-pct">80%</span>
              <div class="progress-bar"><div class="progress-fill" style="width:80%"></div></div>
            </div>
            <span class="proj-status status-hold">Active</span>
          </div>
          <div class="project-row">
            <div>
              <div class="proj-name">Material Consume</div>
              <div class="proj-location">Site Transfer ↗ Data flow</div>
            </div>
            <div class="proj-progress">
              <span class="progress-pct">92%</span>
              <div class="progress-bar"><div class="progress-fill" style="width:92%"></div></div>
            </div>
            <span class="proj-status status-review">Review</span>
          </div>
          <div class="project-row">
            <div>
              <div class="proj-name">Human Resource</div>
              <div class="proj-location">Site Wise ↗ Data flow</div>
            </div>
            <div class="proj-progress">
              <span class="progress-pct">75%</span>
              <div class="progress-bar"><div class="progress-fill" style="width:75%"></div></div>
            </div>
            <span class="proj-status status-active">Active</span>
          </div>
        </div>
        <div class="mini-metrics">
          <div class="metric-box">
            <div class="metric-val amber">3,245</div>
            <div class="metric-key">Total Staff</div>
          </div>
          <div class="metric-box">
            <div class="metric-val">375</div>
            <div class="metric-key">Workers On-site</div>
          </div>
          <div class="metric-box">
            <div class="metric-val">347</div>
            <div class="metric-key">Total Machinery</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features" id="features">
  <div class="section-label">// What We Do</div>
  <h2 class="section-title">Built For The <span class="muted">Site.</span></h2>
  <div class="features-grid">
    <div class="feature-card">
      <span class="feature-number">01</span>
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
      </div>
      <div class="feature-name">Project Planning</div>
      <div class="feature-desc">End-to-end Gantt scheduling, milestone tracking, and resource allocation across multiple sites simultaneously.</div>
    </div>
    <div class="feature-card">
      <span class="feature-number">02</span>
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
      </div>
      <div class="feature-name">Material Procurement</div>
      <div class="feature-desc">Vendor management, purchase orders, delivery tracking, and inventory control — all connected to project budgets.</div>
    </div>
    <div class="feature-card">
      <span class="feature-number">03</span>
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      </div>
      <div class="feature-name">Labour Management</div>
      <div class="feature-desc">Daily attendance, wage calculation, skill-based assignment, and contractor bill management with digital records.</div>
    </div>
    <div class="feature-card">
      <span class="feature-number">04</span>
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div class="feature-name">Financial Control</div>
      <div class="feature-desc">Budget vs actual tracking, cash flow monitoring, invoice management, and GST-ready financial reports.</div>
    </div>
    <div class="feature-card">
      <span class="feature-number">05</span>
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </div>
      <div class="feature-name">Quality & Compliance</div>
      <div class="feature-desc">Site inspection checklists, defect logging, photo documentation, and safety compliance dashboards.</div>
    </div>
    <div class="feature-card">
      <span class="feature-number">06</span>
      <div class="feature-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      <div class="feature-name">Live Reporting</div>
      <div class="feature-desc">Real-time progress reports, automated client updates, and executive dashboards accessible from any device.</div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <h2 class="cta-title">Ready To Build Smarter?</h2>
  <p class="cta-sub">Join hundreds of construction firms already using Eznirman to deliver projects on time and on budget.</p>
  <div class="cta-buttons">
    <a href="tel:+6292237205" class="btn-large">Call Support</a>
    <a href="https://wa.me/916292237205?text=Hello%20Saklin,%20I%20am%20interested%20in%20a%20demo%20of%20EZ%20Nirman%20Software.%20Please%20share%20the%20details." class="btn-outline">
    Get Software Demo
    </a>
    
  </div>
</section>

<!-- FOOTER -->
<footer>
  <span class="footer-copy">© 2026 Eznirman.com — Ranihati Construction PVT. LTD. | Management System</span>
  <ul class="footer-links">
    <li><a href="#">Privacy</a></li>
    <li><a href="#">Terms</a></li>
    <li><a href="#">Support</a></li>
    <li><a href="#">Contact</a></li>
  </ul>
</footer>

<script>
  const burger = document.getElementById('burgerBtn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const closeBtn = document.getElementById('sidebarClose');

  function openSidebar() {
    overlay.style.display = 'block';
    requestAnimationFrame(() => {
      overlay.classList.add('visible');
      sidebar.classList.add('open');
      sidebar.setAttribute('aria-hidden', 'false');
      burger.setAttribute('aria-expanded', 'true');
      burger.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  }

  function closeSidebar() {
    overlay.classList.remove('visible');
    sidebar.classList.remove('open');
    sidebar.setAttribute('aria-hidden', 'true');
    burger.setAttribute('aria-expanded', 'false');
    burger.classList.remove('open');
    document.body.style.overflow = '';
    setTimeout(() => { overlay.style.display = 'none'; }, 350);
  }

  burger.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });
  closeBtn.addEventListener('click', closeSidebar);
  overlay.addEventListener('click', closeSidebar);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && sidebar.classList.contains('open')) closeSidebar();
  });

  sidebar.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 1024) closeSidebar();
    });
  });
</script>
</body>
</html>