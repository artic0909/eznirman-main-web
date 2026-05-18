<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Eznirman — ATM Wallet & Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <!-- jQuery and Select2 libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <style>
    /* ── BASE STYLING & TOKENS ── */
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      -webkit-tap-highlight-color: transparent;
    }

    :root {
      --bg: #070a13;
      --surface: #0e1322;
      --surface-light: #151e33;
      --surface-border: rgba(255, 255, 255, 0.05);
      --primary: #7c6ff7;
      --primary-glow: rgba(124, 111, 247, 0.15);
      --secondary: #e8a020;
      --secondary-glow: rgba(232, 160, 32, 0.12);
      --success: #10b981;
      --danger: #ef4444;
      --text: #f3f5f8;
      --text-muted: #8ea1b4;
      --white: #ffffff;
      --font-outfit: 'Outfit', sans-serif;
      --font-mono: 'DM Mono', monospace;
    }

    body {
      background-color: var(--bg);
      color: var(--text);
      font-family: var(--font-outfit);
      font-weight: 400;
      overflow-x: hidden;
      min-height: 100vh;
      position: relative;
    }

    /* Custom premium Select2 Dark Overrides */
    .select2-container {
      margin-top: 4px;
    }
    .select2-container--default .select2-selection--single {
      background-color: var(--surface-light) !important;
      border: 1px solid var(--surface-border) !important;
      border-radius: 12px !important;
      height: 46px !important;
      display: flex !important;
      align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: var(--white) !important;
      font-family: var(--font-outfit) !important;
      font-size: 14px !important;
      padding-left: 6px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 44px !important;
      right: 10px !important;
    }
    .select2-dropdown {
      background-color: var(--surface) !important;
      border: 1px solid rgba(255,255,255,0.08) !important;
      border-radius: 12px !important;
      box-shadow: 0 10px 24px rgba(0,0,0,0.6) !important;
      z-index: 99999 !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
      background-color: var(--surface-light) !important;
      border: 1px solid var(--surface-border) !important;
      color: var(--white) !important;
      border-radius: 8px !important;
      outline: none !important;
      font-family: var(--font-outfit) !important;
      padding: 6px 10px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: var(--primary) !important;
      color: var(--white) !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
      background-color: rgba(124, 111, 247, 0.15) !important;
      color: var(--white) !important;
    }
    .select2-results__option {
      font-family: var(--font-outfit) !important;
      font-size: 13px !important;
      color: var(--text-muted) !important;
      padding: 10px 14px !important;
    }
    .select2-container--default .select2-results__option[aria-disabled=true] {
      color: rgba(255,255,255,0.2) !important;
    }

    /* Cyberpunk Grid Background Overlay */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: repeating-linear-gradient(
        -55deg,
        transparent,
        transparent 50px,
        rgba(124, 111, 247, 0.01) 50px,
        rgba(124, 111, 247, 0.01) 51px
      );
      pointer-events: none;
      z-index: 0;
    }

    /* Ambient Glowing Blobs */
    .blob {
      position: fixed;
      border-radius: 50%;
      filter: blur(140px);
      pointer-events: none;
      z-index: 0;
      opacity: 0.1;
    }
    .blob-1 { width: 400px; height: 400px; background: var(--primary); top: -100px; right: -50px; }
    .blob-2 { width: 350px; height: 350px; background: var(--secondary); bottom: 10%; left: -80px; }

    /* ── SCENIC GRID LAYOUT ── */
    .app-layout {
      display: grid;
      grid-template-columns: 1fr;
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }

    @media (min-width: 1024px) {
      .app-layout {
        grid-template-columns: 280px 1fr;
      }
    }

    /* ── DESKTOP LEFT SIDEBAR ── */
    .desktop-sidebar {
      display: none;
      background-color: var(--surface);
      border-right: 1px solid var(--surface-border);
      flex-direction: column;
      height: 100vh;
      position: sticky;
      top: 0;
      z-index: 110;
      padding: 32px 20px 24px;
    }

    @media (min-width: 1024px) {
      .desktop-sidebar {
        display: flex;
      }
    }

    .sidebar-logo {
      display: flex;
      align-items: baseline;
      gap: 1px;
      text-decoration: none;
      margin-bottom: 32px;
      padding-left: 12px;
    }

    .logo-ez {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 28px;
      color: var(--secondary);
      letter-spacing: 2px;
    }

    .logo-nirman {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 28px;
      color: var(--white);
      letter-spacing: 2px;
    }

    .sidebar-profile-card {
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid var(--surface-border);
      border-radius: 16px;
      padding: 20px 16px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 12px;
      margin-bottom: 28px;
    }

    .d-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      border: 2px solid var(--primary);
      box-shadow: 0 0 12px var(--primary-glow);
      background-color: var(--surface-light);
      overflow: hidden;
    }

    .d-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .d-name {
      font-size: 15px;
      font-weight: 600;
      color: var(--white);
    }

    .d-role-pill {
      font-size: 9px;
      font-family: var(--font-mono);
      color: var(--secondary);
      background: var(--secondary-glow);
      border: 1px solid rgba(232, 160, 32, 0.2);
      padding: 3px 10px;
      border-radius: 6px;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .sidebar-menu-links {
      display: flex;
      flex-direction: column;
      gap: 6px;
      flex: 1;
    }

    .menu-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      border-radius: 10px;
      transition: all 0.25s;
    }

    .menu-link:hover, .menu-link.active {
      color: var(--white);
      background-color: var(--surface-light);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .menu-link:hover svg, .menu-link.active svg {
      color: var(--primary);
      transform: translateX(2px);
    }

    .menu-link svg {
      width: 16px;
      height: 16px;
      transition: all 0.25s;
      stroke-width: 1.8;
    }

    .d-logout-wrap {
      border-top: 1px solid var(--surface-border);
      padding-top: 20px;
    }

    /* ── MAIN WORKSPACE WRAPPER ── */
    .main-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      min-width: 0;
    }

    /* ── HEADER NAVBAR ── */
    header {
      height: 72px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      border-bottom: 1px solid var(--surface-border);
      background: rgba(14, 19, 34, 0.6);
      backdrop-filter: blur(10px);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    @media (min-width: 1024px) {
      header {
        padding: 0 32px;
        background: rgba(7, 10, 19, 0.6);
      }
    }

    .header-left-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--white);
      display: none;
    }

    @media (min-width: 1024px) {
      .header-left-title {
        display: block;
      }
    }

    .user-info-mobile {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    @media (min-width: 1024px) {
      .user-info-mobile {
        display: none;
      }
    }

    .avatar-wrapper-m {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid var(--primary);
      background-color: var(--surface-light);
    }

    .avatar-wrapper-m img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .burger-btn {
      width: 40px;
      height: 40px;
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 5px;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (min-width: 1024px) {
      .burger-btn {
        display: none;
      }
    }

    .burger-btn span {
      display: block;
      width: 20px;
      height: 2px;
      background-color: var(--text);
      transition: all 0.3s;
    }

    .burger-btn.active span:nth-child(1) {
      transform: translateY(7px) rotate(45deg);
      background-color: var(--primary);
    }

    .burger-btn.active span:nth-child(2) {
      opacity: 0;
    }

    .burger-btn.active span:nth-child(3) {
      transform: translateY(-7px) rotate(-45deg);
      background-color: var(--primary);
    }

    /* ── RESPONSIVE DYNAMIC CONTENT AREA ── */
    main {
      flex: 1;
      padding: 20px;
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      align-content: start;
    }

    @media (min-width: 768px) and (max-width: 1023px) {
      main {
        grid-template-columns: 1.1fr 0.9fr;
        padding: 24px;
        gap: 24px;
      }
    }

    @media (min-width: 1024px) {
      main {
        grid-template-columns: 1.65fr 1.35fr;
        padding: 32px;
        gap: 32px;
      }
    }

    /* COLUMN GROUPING */
    .col-left {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    @media (min-width: 1024px) {
      .col-left {
        gap: 24px;
      }
    }

    .col-right {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    @media (min-width: 1024px) {
      .col-right {
        gap: 24px;
      }
    }

    /* ── ATM DEBIT CARD BRANDING ── */
    .wallet-card {
      background: linear-gradient(135deg, #12192a 0%, #1d1b3b 50%, #0c0f1e 100%);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      padding: 24px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
      display: flex;
      flex-direction: column;
      height: 220px;
      justify-content: space-between;
      transition: all 0.3s;
    }

    .wallet-card:hover {
      box-shadow: 0 20px 48px rgba(124, 111, 247, 0.20);
      border-color: rgba(124, 111, 247, 0.3);
    }

    /* Gloss overlay for metallic reflections */
    .wallet-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(125deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0.03) 40%, transparent 60%);
      pointer-events: none;
    }

    /* Circuit card lines */
    .wallet-card::after {
      content: '';
      position: absolute;
      bottom: -30px;
      left: -30px;
      width: 180px;
      height: 180px;
      background: radial-gradient(circle, rgba(124, 111, 247, 0.15) 0%, transparent 80%);
      pointer-events: none;
    }

    .atm-header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 10;
    }

    .atm-brand-ez {
      display: flex;
      align-items: baseline;
      gap: 1px;
    }

    .atm-brand-ez .ez {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 20px;
      color: var(--secondary);
      letter-spacing: 1px;
    }

    .atm-brand-ez .nirman {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 20px;
      color: var(--white);
      letter-spacing: 1px;
    }

    .atm-brand-rc {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: 1px;
      color: var(--text-muted);
      text-transform: uppercase;
      text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    .atm-chip-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
      z-index: 10;
    }

    .atm-chip {
      width: 38px;
      height: 28px;
      background: linear-gradient(135deg, #e5c060 0%, #b28c2e 50%, #f7dc8c 100%);
      border-radius: 6px;
      position: relative;
      box-shadow: inset 0 1px 1px rgba(255,255,255,0.3);
    }

    .atm-chip::after {
      content: '';
      position: absolute;
      inset: 4px;
      border: 1px solid rgba(0, 0, 0, 0.15);
      border-radius: 4px;
      background: repeating-linear-gradient(90deg, transparent, transparent 4px, rgba(0,0,0,0.1) 4px, rgba(0,0,0,0.1) 5px);
    }

    .atm-contactless {
      color: var(--text-muted);
      display: flex;
    }

    .atm-balance-display {
      margin-top: 4px;
      z-index: 10;
    }

    .atm-card-number {
      font-family: var(--font-mono);
      font-size: 15px;
      letter-spacing: 2px;
      color: var(--white);
      margin-top: 12px;
      text-shadow: 0 1px 2px rgba(0,0,0,0.8);
      z-index: 10;
    }

    .atm-details-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-top: auto;
      z-index: 10;
    }

    .atm-holder-name {
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--text);
    }

    .atm-expiry {
      font-family: var(--font-mono);
      font-size: 11px;
      color: var(--text-muted);
    }

    /* ── MONTHLY TRANSACTION OVERVIEW PANEL ── */
    .monthly-summary-card {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 20px;
      padding: 20px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .summary-stat-box {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .summary-stat-lbl {
      font-size: 10px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .summary-stat-val {
      font-size: 18px;
      font-weight: 700;
      color: var(--white);
    }

    .summary-stat-val.highlight {
      color: var(--secondary);
      font-family: var(--font-mono);
    }

    /* ── WALLET METRICS (SPEND BAR) ── */
    .spend-metrics {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 20px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .metrics-header {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
    }

    .metrics-title {
      font-weight: 500;
      color: var(--text);
    }

    .metrics-amount {
      color: var(--text-muted);
    }

    .progress-track {
      width: 100%;
      height: 10px;
      background: var(--surface-light);
      border-radius: 6px;
      overflow: hidden;
      position: relative;
    }

    .progress-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--primary) 0%, #a59ef9 100%);
      border-radius: 6px;
      transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 0 12px var(--primary-glow);
    }

    .metrics-footer {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      color: var(--text-muted);
      font-family: var(--font-mono);
    }

    /* ── QUICK ACTIONS GRID ── */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }

    .action-btn {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 20px;
      padding: 16px 8px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .action-btn:hover {
      border-color: var(--primary);
      background-color: var(--surface-light);
      transform: translateY(-2px);
    }

    .action-btn:active {
      transform: translateY(0);
    }

    .action-icon {
      width: 48px;
      height: 48px;
      background: var(--primary-glow);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--primary);
      transition: all 0.3s;
    }

    .action-btn:hover .action-icon {
      color: var(--white);
      background: var(--primary);
      box-shadow: 0 0 14px var(--primary-glow);
    }

    .action-label {
      font-size: 11px;
      font-weight: 500;
      color: var(--text);
      text-align: center;
    }

    /* ── TRANSACTIONS LIST ── */
    .transactions-section {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .section-title {
      font-size: 16px;
      font-weight: 600;
      color: var(--white);
    }

    .filter-tabs {
      display: flex;
      background: var(--surface);
      border: 1px solid var(--surface-border);
      padding: 4px;
      border-radius: 12px;
      gap: 2px;
    }

    .filter-tab {
      background: transparent;
      border: none;
      color: var(--text-muted);
      padding: 8px 16px;
      font-size: 11px;
      font-weight: 600;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .filter-tab.active {
      background: var(--surface-light);
      color: var(--primary);
      box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }

    .ledger-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .ledger-item {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 20px;
      padding: 16px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ledger-item:hover {
      border-color: rgba(255,255,255,0.12);
      transform: translateX(3px);
      background-color: rgba(255,255,255,0.01);
    }

    .ledger-item:active {
      background-color: var(--surface-light);
    }

    .ledger-left {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .ledger-icon-wrap {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }

    .ledger-icon-wrap.credit {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .ledger-icon-wrap.debit {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
    }

    .ledger-details {
      display: flex;
      flex-direction: column;
      gap: 3px;
    }

    .ledger-desc {
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      display: -webkit-box;
      -webkit-line-clamp: 1;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .ledger-sub {
      font-size: 11px;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .ledger-sub::before {
      content: '';
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background-color: var(--text-muted);
    }

    .ledger-right {
      text-align: right;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 6px;
    }

    .ledger-amount {
      font-size: 15px;
      font-weight: 700;
      font-family: var(--font-mono);
    }

    .ledger-amount.credit {
      color: var(--success);
    }

    .ledger-amount.debit {
      color: var(--danger);
    }

    .status-badge {
      font-size: 8px;
      font-weight: 700;
      text-transform: uppercase;
      padding: 3px 8px;
      border-radius: 6px;
      letter-spacing: 0.5px;
    }

    .status-badge.success {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
    }

    .status-badge.pending {
      background: rgba(232, 160, 32, 0.1);
      color: var(--secondary);
    }

    /* ── HIGH FIDELITY SITE CONTROL PANEL ── */
    .site-panel-card {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 24px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .site-panel-header {
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid var(--surface-border);
      padding-bottom: 16px;
    }

    .site-icon {
      width: 40px;
      height: 40px;
      background: rgba(232, 160, 32, 0.08);
      border: 1px solid rgba(232, 160, 32, 0.2);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--secondary);
    }

    .site-meta-title {
      font-size: 15px;
      font-weight: 600;
      color: var(--white);
    }

    .site-meta-sub {
      font-size: 11px;
      color: var(--text-muted);
    }

    .checklist-section {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .check-title {
      font-size: 12px;
      font-family: var(--font-mono);
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .check-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      color: var(--text);
    }

    .check-box {
      width: 18px;
      height: 18px;
      border-radius: 6px;
      border: 1px solid var(--surface-border);
      background-color: var(--surface-light);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--success);
      font-weight: 700;
      font-size: 11px;
    }

    .check-box.checked {
      border-color: var(--success);
      background-color: rgba(16, 185, 129, 0.1);
    }

    /* ── BUDGET DISTRIBUTION BREAKDOWN ── */
    .budget-chart-card {
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 24px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      gap: 18px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .chart-item {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .chart-lbl-row {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
    }

    .chart-lbl {
      color: var(--text);
      font-weight: 500;
    }

    .chart-pct {
      color: var(--text-muted);
      font-family: var(--font-mono);
    }

    .chart-bar-track {
      width: 100%;
      height: 6px;
      background: var(--surface-light);
      border-radius: 3px;
      overflow: hidden;
    }

    .chart-bar-fill {
      height: 100%;
      border-radius: 3px;
    }

    /* ── SLIDING RIGHT SIDEBAR / DRAWER FOR MOBILE ── */
    .sidebar {
      position: fixed;
      top: 0;
      right: 0;
      bottom: 0;
      width: 280px;
      background: rgba(14, 19, 34, 0.85);
      backdrop-filter: blur(14px);
      border-left: 1px solid var(--surface-border);
      z-index: 150;
      display: flex;
      flex-direction: column;
      transform: translateX(100%);
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: -10px 0 30px rgba(0,0,0,0.5);
    }

    .sidebar.open {
      transform: translateX(0);
    }

    .sidebar-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(3px);
      z-index: 140;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.4s ease;
    }

    .sidebar-overlay.show {
      opacity: 1;
      pointer-events: auto;
    }

    .sidebar-header {
      padding: 24px 20px;
      border-bottom: 1px solid var(--surface-border);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 12px;
    }

    .sidebar-avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      border: 3px solid var(--primary);
      box-shadow: 0 0 15px var(--primary-glow);
      background-color: var(--surface-light);
      overflow: hidden;
    }

    .sidebar-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .sidebar-user-name {
      font-size: 16px;
      font-weight: 700;
      color: var(--white);
    }

    .sidebar-user-code {
      font-family: var(--font-mono);
      font-size: 11px;
      color: var(--secondary);
      background: rgba(232, 160, 32, 0.08);
      border: 1px solid rgba(232, 160, 32, 0.2);
      padding: 2px 8px;
      border-radius: 6px;
    }

    .sidebar-links {
      flex: 1;
      padding: 20px 0;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 24px;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.25s;
      border-left: 3px solid transparent;
    }

    .sidebar-link:hover, .sidebar-link.active {
      color: var(--primary);
      background: rgba(124, 111, 247, 0.05);
      border-left-color: var(--primary);
    }

    .sidebar-link svg {
      width: 18px;
      height: 18px;
      stroke-width: 1.5;
    }

    .sidebar-footer {
      padding: 20px 24px;
      border-top: 1px solid var(--surface-border);
    }

    .logout-btn {
      width: 100%;
      padding: 12px;
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.2);
      border-radius: 12px;
      color: var(--danger);
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.3s;
    }

    .logout-btn:hover {
      background: var(--danger);
      color: var(--white);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    }

    /* ── INTERACTIVE MODALS ── */
    .modal {
      position: fixed;
      inset: 0;
      z-index: 200;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      opacity: 0;
      pointer-events: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal.open {
      opacity: 1;
      pointer-events: auto;
    }

    .modal-backdrop {
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(8px);
    }

    .modal-content {
      position: relative;
      background: var(--surface);
      border: 1px solid var(--surface-border);
      border-radius: 24px;
      width: 100%;
      max-width: 420px;
      padding: 28px;
      box-shadow: 0 24px 48px rgba(0,0,0,0.7);
      transform: translateY(20px);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 10;
    }

    .modal.open .modal-content {
      transform: translateY(0);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .modal-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--white);
    }

    .modal-close {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: var(--surface-light);
      border: none;
      color: var(--text-muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }

    .modal-close:hover {
      color: var(--white);
      background: var(--primary);
    }

    /* Modal Form Styling */
    .form-group {
      margin-bottom: 16px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-lbl {
      font-size: 10px;
      font-family: var(--font-mono);
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .form-ctrl {
      width: 100%;
      background: var(--surface-light);
      border: 1px solid var(--surface-border);
      color: var(--white);
      font-family: var(--font-outfit);
      font-size: 14px;
      padding: 12px 14px;
      border-radius: 12px;
      outline: none;
      transition: all 0.25s;
    }

    .form-ctrl:focus {
      border-color: var(--primary);
      box-shadow: 0 0 8px rgba(124,111,247,0.25);
    }

    .modal-submit {
      width: 100%;
      padding: 14px;
      background: var(--primary);
      color: var(--white);
      border: none;
      border-radius: 12px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 10px;
      transition: all 0.3s;
    }

    .modal-submit:hover {
      box-shadow: 0 0 15px rgba(124,111,247,0.4);
      background: #8b80f8;
    }

    /* Mock QR Camera Scanner Frame */
    .qr-cam-frame {
      width: 100%;
      height: 220px;
      background: #000000;
      border-radius: 16px;
      position: relative;
      overflow: hidden;
      margin-bottom: 16px;
    }

    .qr-cam-scanner {
      position: absolute;
      inset: 30px;
      border: 2px dashed rgba(255,255,255,0.3);
      border-radius: 8px;
    }

    .qr-laser {
      position: absolute;
      top: 30px;
      left: 30px;
      right: 30px;
      height: 2px;
      background: var(--success);
      box-shadow: 0 0 8px var(--success);
      animation: scanLaser 2s linear infinite;
    }

    @keyframes scanLaser {
      0%, 100% { top: 30px; }
      50% { top: 188px; }
    }

    .qr-loading-text {
      position: absolute;
      bottom: 12px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 11px;
      color: var(--text-muted);
      font-family: var(--font-mono);
    }

    /* Success Popups */
    .toast-success {
      position: fixed;
      top: 24px;
      left: 50%;
      transform: translate(-50%, -100px);
      background: var(--surface);
      border: 1px solid var(--success);
      border-left: 4px solid var(--success);
      padding: 14px 20px;
      border-radius: 12px;
      box-shadow: 0 10px 24px rgba(0,0,0,0.5);
      z-index: 300;
      display: flex;
      align-items: center;
      gap: 12px;
      opacity: 0;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      pointer-events: none;
      width: 90%;
      max-width: 400px;
    }

    .toast-success.show {
      transform: translate(-50%, 0);
      opacity: 1;
    }

    /* Detailed Receipt Ledger Modal */
    .receipt-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px dashed rgba(255,255,255,0.04);
      font-size: 13px;
    }
    .receipt-lbl {
      color: var(--text-muted);
    }
    .receipt-val {
      font-weight: 500;
      color: var(--text);
    }
    .receipt-val.highlight {
      font-family: var(--font-mono);
      font-weight: 700;
    }

    /* ── BOTTOM APP NAV FOR MOBILE ── */
    .bottom-nav {
      height: 64px;
      background: rgba(14, 19, 34, 0.8);
      backdrop-filter: blur(10px);
      border-top: 1px solid var(--surface-border);
      display: flex;
      justify-content: space-around;
      align-items: center;
      position: sticky;
      bottom: 0;
      z-index: 100;
    }

    @media (min-width: 1024px) {
      .bottom-nav {
        display: none;
      }
    }

    .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 10px;
      font-weight: 500;
      cursor: pointer;
      transition: color 0.25s;
    }

    .nav-item.active, .nav-item:hover {
      color: var(--primary);
    }

    .nav-item svg {
      width: 20px;
      height: 20px;
      stroke-width: 1.5;
    }
  </style>
</head>
<body>

  <!-- AMBENT COLOR GLOWS -->
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>

  <!-- CORE DEVICE LAYOUT -->
  <div class="app-layout">

    <!-- DESKTOP FIXED SIDEBAR -->
    <aside class="desktop-sidebar">
      <div class="sidebar-logo">
        <span class="logo-ez">EZ</span><span class="logo-nirman">NIRMAN</span>
      </div>

      <div class="sidebar-profile-card">
        <div class="d-avatar">
          <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . Auth::user()->code }}" alt="{{ Auth::user()->name }}">
        </div>
        <div class="d-name">{{ Auth::user()->name }}</div>
        <div class="d-role-pill">Role: {{ Auth::user()->role }}</div>
      </div>

      <nav class="sidebar-menu-links">
        <a href="#" class="menu-link active">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
          SaaS Dashboard
        </a>
        <a href="#" class="menu-link" onclick="openModal('transferModal');">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          Transfer Funds
        </a>
        <a href="#" class="menu-link" onclick="openModal('requestModal');">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Request Allocation
        </a>
        <a href="#" class="menu-link" onclick="document.getElementById('historyScrollTrigger').click();">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 8v4l3 3"></path><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"></path></svg>
          Transaction History
        </a>
      </nav>

      <div class="d-logout-wrap">
        <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
          </svg>
          Log Out
        </button>
      </div>
    </aside>

    <!-- MAIN RIGHT VIEWPORT -->
    <div class="main-wrapper">

      <!-- HEADER NAVBAR -->
      <header>
        <span class="header-left-title">Supervisor Command Console</span>

        <!-- Mobile User Greeting Info -->
        <div class="user-info-mobile">
          <div class="avatar-wrapper-m">
            <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . Auth::user()->code }}" alt="{{ Auth::user()->name }}">
          </div>
          <div style="display: flex; flex-direction: column;">
            <span style="font-size: 10px; color: var(--text-muted); text-transform: uppercase;">Portal</span>
            <span style="font-size: 14px; font-weight: 600;">{{ Auth::user()->name }}</span>
          </div>
        </div>

        <button class="burger-btn" id="burgerBtn" aria-label="Toggle navigation menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </header>

      <!-- RESPONSIVE GRID CONTENT -->
      <main>

        <!-- LEFT COLUMN: ATM WALLET CARD, MONTHLY OVERVIEW STATS, TRANSACTIONS -->
        <div class="col-left">

          <!-- ATM CARD DESIGN WITH MULTI-BRANDING -->
          <section class="wallet-card">
            <div class="atm-header-row">
              <div class="atm-brand-ez">
                <span class="ez">EZ</span><span class="nirman">NIRMAN</span>
              </div>
              <div class="atm-brand-rc">Ranihati Construction</div>
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

          <!-- Monthly Budget Limits -->
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
                <div class="ledger-item" data-type="{{ $tx->type }}" onclick="openReceiptModal('{{ $tx->note }}', 'EZ-TX-{{ $tx->id }}', '{{ $tx->type === 'credit' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}', 'Success', '{{ $tx->date->format('d M Y · h:i:s A') }}', '{{ $tx->accountcode ? $tx->accountcode->name : 'N/A' }}', '{{ $tx->note }}', '₹{{ number_format($tx->balance_after, 2) }}')">
                  <div class="ledger-left">
                    <div class="ledger-icon-wrap {{ $tx->type }}">
                      {{ $tx->type === 'credit' ? '✓' : '↑' }}
                    </div>
                    <div class="ledger-details">
                      <span class="ledger-desc">{{ $tx->note }}</span>
                      <span class="ledger-sub">
                        {{ $tx->accountcode ? $tx->accountcode->name : 'General' }} · {{ $tx->date->format('d M Y · h:i:s A') }}
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

            <div class="action-btn" onclick="openModal('transferModal')">
              <div class="action-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
              </div>
              <span class="action-label">Transfer</span>
            </div>

            <div class="action-btn" onclick="openModal('requestModal')">
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

          <!-- Active Site panel details -->
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

      </main>

      <!-- BOTTOM MOBILE APP NAVIGATION -->
      <nav class="bottom-nav">
        <a href="#" class="nav-item active">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
            <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
            <line x1="6" y1="6" x2="6.01" y2="6"></line>
            <line x1="6" y1="18" x2="6.01" y2="18"></line>
          </svg>
          <span>Portal</span>
        </a>
        <a href="#" class="nav-item" onclick="openModal('transferModal')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="1" x2="12" y2="23"></line>
            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
          </svg>
          <span>Wallet</span>
        </a>
        <a href="#" class="nav-item" onclick="openModal('qrModal')">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
            <circle cx="12" cy="13" r="4"></circle>
          </svg>
          <span>Scan</span>
        </a>
        <a href="#" class="nav-item" onclick="toggleSidebar()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <span>Profile</span>
        </a>
      </nav>

    </div>

  </div>

  <!-- SLIDING SIDEBAR DRAWER (FOR MOBILE/TABLET TAPS) -->
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-avatar">
        <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . Auth::user()->code }}" alt="{{ Auth::user()->name }}">
      </div>
      <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
      <div class="sidebar-user-code">ID: {{ Auth::user()->code }}</div>
    </div>

    <nav class="sidebar-links">
      <a href="#" class="sidebar-link active" onclick="toggleSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
        SaaS Dashboard
      </a>
      <a href="#" class="sidebar-link" onclick="toggleSidebar(); openModal('transferModal');">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        Transfer Funds
      </a>
      <a href="#" class="sidebar-link" onclick="toggleSidebar(); openModal('requestModal');">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Request Allocation
      </a>
      <a href="#" class="sidebar-link" onclick="toggleSidebar(); document.getElementById('historyScrollTrigger').click();">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 8v4l3 3"></path><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"></path></svg>
        Transaction History
      </a>
    </nav>

    <div class="sidebar-footer">
      <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        Log Out
      </button>
    </div>
  </aside>

  <!-- ── MODAL: TRANSFER MONEY ── -->
  <div class="modal" id="transferModal">
    <div class="modal-backdrop" onclick="closeModal('transferModal')"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Transfer Funds</h3>
        <button class="modal-close" onclick="closeModal('transferModal')">×</button>
      </div>
      <form id="transferForm" onsubmit="handleTransferSubmit(event)">
        <!-- Disbursal Date -->
        <div class="form-group">
          <label class="form-lbl">Disbursal Date</label>
          <input type="date" class="form-ctrl" id="transferDate" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- Pay To Choice -->
        <div class="form-group">
          <label class="form-lbl">Pay To</label>
          <select class="form-ctrl" id="transferPayTo" required onchange="togglePayToFields()">
            <option value="worker" selected>Worker</option>
            <option value="contractor">Contractor</option>
            <option value="others">Others</option>
          </select>
        </div>

        <!-- Conditional Worker search via Select2 -->
        <div class="form-group" id="workerCodeGroup">
          <label class="form-lbl">Worker Code & Name</label>
          <select class="form-ctrl select2-worker" id="transferWorkerCode" style="width: 100%;">
            <option value="" disabled selected>Search Worker Code or Name...</option>
            @foreach($workers as $worker)
              <option value="{{ $worker->code }}">{{ $worker->code }} — {{ $worker->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Conditional Contractor reference -->
        <div class="form-group" id="contractorCodeGroup" style="display: none;">
          <label class="form-lbl">Contractor Name / Reference</label>
          <input type="text" class="form-ctrl" id="transferContractorCode" placeholder="Enter contractor name or code">
        </div>

        <!-- Category Reference -->
        <div class="form-group">
          <label class="form-lbl">Category Reference</label>
          <select class="form-ctrl" required id="transferAccountCode">
            @foreach($accountCodes as $ac)
              <option value="{{ $ac->id }}">{{ $ac->code }} — {{ $ac->name }}</option>
            @endforeach
          </select>
        </div>

        <!-- Amount -->
        <div class="form-group">
          <label class="form-lbl">Transfer Amount (₹)</label>
          <input type="number" class="form-ctrl" placeholder="Enter amount" min="1" max="{{ $wallet->current_balance }}" required id="transferAmount">
        </div>

        <!-- Remarks -->
        <div class="form-group">
          <label class="form-lbl">Remarks / Purpose</label>
          <input type="text" class="form-ctrl" placeholder="e.g. concrete cement or local wages" required id="transferRemarks">
        </div>

        <button type="submit" class="modal-submit">Confirm Disbursal</button>
      </form>
    </div>
  </div>

  <!-- ── MODAL: REQUEST CASH ── -->
  <div class="modal" id="requestModal">
    <div class="modal-backdrop" onclick="closeModal('requestModal')"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Request Budget Allocation</h3>
        <button class="modal-close" onclick="closeModal('requestModal')">×</button>
      </div>
      <form id="requestForm" onsubmit="handleRequestSubmit(event)">
        <!-- Disbursal Date -->
        <div class="form-group">
          <label class="form-lbl">Disbursal Date</label>
          <input type="date" class="form-ctrl" id="requestDate" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- From Name Reference -->
        <div class="form-group">
          <label class="form-lbl">From</label>
          <input type="text" class="form-ctrl" id="requestFrom" placeholder="Enter your name" value="{{ Auth::user()->name }}" required>
        </div>

        <!-- Required Amount -->
        <div class="form-group">
          <label class="form-lbl">Required Amount (₹)</label>
          <input type="number" class="form-ctrl" placeholder="e.g. ₹15,000" min="1" required id="requestAmount">
        </div>

        <!-- Justification Details -->
        <div class="form-group">
          <label class="form-lbl">Justification Details</label>
          <input type="text" class="form-ctrl" placeholder="Provide justification remarks" required id="requestJustification">
        </div>

        <button type="submit" class="modal-submit">Submit Request</button>
      </form>
    </div>
  </div>

  <!-- ── MODAL: SCAN QR CODE ── -->
  <div class="modal" id="qrModal">
    <div class="modal-backdrop" onclick="closeModal('qrModal')"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">QR Scanner</h3>
        <button class="modal-close" onclick="closeModal('qrModal')">×</button>
      </div>
      <div class="qr-cam-frame">
        <div class="qr-cam-scanner"></div>
        <div class="qr-laser"></div>
        <div class="qr-loading-text">Align active merchant or site supervisor QR</div>
      </div>
      <button class="modal-submit" onclick="closeModal('qrModal'); showNotification('Scan Verified', 'Merchant scanned successfully!')">Simulate QR Scan</button>
    </div>
  </div>

  <!-- ── MODAL: LEDGER RECEIPT ── -->
  <div class="modal" id="receiptModal">
    <div class="modal-backdrop" onclick="closeModal('receiptModal')"></div>
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" style="font-family: var(--font-mono); font-size: 14px; letter-spacing: 1px;">LEDGER RECEIPT</h3>
        <button class="modal-close" onclick="closeModal('receiptModal')">×</button>
      </div>
      <div id="receiptContent">
        <!-- Loaded dynamically via JS -->
      </div>
    </div>
  </div>

  <!-- STATE SUCCESS TOAST -->
  <div class="toast-success" id="successToast">
    <div style="background: rgba(16, 185, 129, 0.1); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); font-weight: 700;">✓</div>
    <div style="display: flex; flex-direction: column;">
      <span style="font-weight: 600; font-size: 13px; color: var(--white);" id="toastTitle">Success Action</span>
      <span style="font-size: 11px; color: var(--text-muted);" id="toastDesc">Operation processed successfully.</span>
    </div>
  </div>

  <!-- INTERACTION SCRIPTS -->
  <script>
    // Sidebar Toggling for mobile
    const burgerBtn = document.getElementById('burgerBtn');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
      const open = sidebar.classList.toggle('open');
      burgerBtn.classList.toggle('active', open);
      sidebarOverlay.classList.toggle('show', open);
    }

    burgerBtn.addEventListener('click', toggleSidebar);

    // Modal Handling
    function openModal(id) {
      document.getElementById(id).classList.add('open');
    }
    function closeModal(id) {
      document.getElementById(id).classList.remove('open');
    }

    // Success Toast Notification
    function showNotification(title, description = "Operation completed successfully.") {
      const toast = document.getElementById('successToast');
      document.getElementById('toastTitle').textContent = title;
      document.getElementById('toastDesc').textContent = description;
      toast.classList.add('show');
      setTimeout(() => {
        toast.classList.remove('show');
      }, 4000);
    }

    // History Scroller Trigger
    document.getElementById('historyScrollTrigger').addEventListener('click', () => {
      document.getElementById('ledgerSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    // Filter transaction lists
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

    // Receipt details drawer popup loader
    function openReceiptModal(title, refId, amount, status, date, target, desc, balanceAfter) {
      const isExpense = amount.startsWith('-');
      const amtColor = isExpense ? 'var(--danger)' : 'var(--success)';
      const badgeStyle = status === 'Success' ? 'status-badge success' : 'status-badge pending';

      const receiptHTML = `
        <div style="text-align: center; margin-bottom: 24px;">
          <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px;">Transfer Value</div>
          <div style="font-size: 32px; font-weight: 700; color: ${amtColor}; font-family: var(--font-mono); margin: 6px 0;">${amount}</div>
          <span class="${badgeStyle}">${status}</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 4px; margin-bottom: 12px;">
          <div class="receipt-row">
            <span class="receipt-lbl">Reference ID</span>
            <span class="receipt-val highlight">${refId}</span>
          </div>
          <div class="receipt-row">
            <span class="receipt-lbl">Action Title</span>
            <span class="receipt-val">${title}</span>
          </div>
          <div class="receipt-row">
            <span class="receipt-lbl">Account Category</span>
            <span class="receipt-val">${target}</span>
          </div>
          <div class="receipt-row">
            <span class="receipt-lbl">Reference Timestamp</span>
            <span class="receipt-val">${date}</span>
          </div>
          <div class="receipt-row">
            <span class="receipt-lbl">Balance After Tx</span>
            <span class="receipt-val highlight" style="color: var(--secondary);">${balanceAfter}</span>
          </div>
          <div class="receipt-row" style="border-bottom: none;">
            <span class="receipt-lbl">Remarks Details</span>
            <span class="receipt-val" style="text-align: right; max-width: 60%; word-break: break-word;">${desc}</span>
          </div>
        </div>
      `;

      document.getElementById('receiptContent').innerHTML = receiptHTML;
      openModal('receiptModal');
    }

    // Toggle conditional fields based on Pay To selection
    function togglePayToFields() {
      const payTo = document.getElementById('transferPayTo').value;
      const workerGroup = document.getElementById('workerCodeGroup');
      const contractorGroup = document.getElementById('contractorCodeGroup');
      
      workerGroup.style.display = 'none';
      contractorGroup.style.display = 'none';
      
      if (payTo === 'worker') {
        workerGroup.style.display = 'block';
      } else if (payTo === 'contractor') {
        contractorGroup.style.display = 'block';
      }
    }

    // Initialize Select2 search dropdown on document ready
    $(document).ready(function() {
      if (typeof $.fn.select2 !== 'undefined') {
        $('.select2-worker').select2({
          dropdownParent: $('#transferModal'),
          placeholder: "Search Worker Code or Name..."
        });
      }
      togglePayToFields();
    });

    // Ajax Submit for Transfer Funds
    function handleTransferSubmit(event) {
      event.preventDefault();
      const date = document.getElementById('transferDate').value;
      const pay_to = document.getElementById('transferPayTo').value;
      
      let pay_to_code = '';
      if (pay_to === 'worker') {
        pay_to_code = document.getElementById('transferWorkerCode').value;
        if (!pay_to_code) {
          alert('Please select a Worker Code from the search list.');
          return;
        }
      } else if (pay_to === 'contractor') {
        pay_to_code = document.getElementById('transferContractorCode').value.trim();
        if (!pay_to_code) {
          alert('Please enter a Contractor Name or Reference.');
          return;
        }
      }

      const accountcode_id = document.getElementById('transferAccountCode').value;
      const amount = parseFloat(document.getElementById('transferAmount').value);
      const remarks = document.getElementById('transferRemarks').value.trim();

      fetch("{{ route('user.transaction.store') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
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
          closeModal('transferModal');
          showNotification('Disbursal Successful', `Sent ₹${amount.toLocaleString('en-IN')} to ${pay_to.toUpperCase()} ${pay_to_code}`);
          document.getElementById('transferForm').reset();
          
          setTimeout(() => {
            window.location.reload();
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

    // Ajax Submit for Request Cash
    function handleRequestSubmit(event) {
      event.preventDefault();
      const date = document.getElementById('requestDate').value;
      const from = document.getElementById('requestFrom').value.trim();
      const amount = parseFloat(document.getElementById('requestAmount').value);
      const justification = document.getElementById('requestJustification').value.trim();

      fetch("{{ route('user.transaction.store') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
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
          closeModal('requestModal');
          showNotification('Request Filed', `Site budget request of ₹${amount.toLocaleString('en-IN')} successfully filed.`);
          document.getElementById('requestForm').reset();
          
          setTimeout(() => {
            window.location.reload();
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
</body>
</html>
