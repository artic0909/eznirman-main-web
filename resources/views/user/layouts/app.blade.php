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

    .header-left-title {
      font-size: 15px;
      font-weight: 700;
      color: var(--white);
      letter-spacing: 0.5px;
      display: flex;
      flex-direction: column;
      flex: 1;
      min-width: 0;
      margin-right: 12px;
    }

    .header-left-title > span:first-child {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .header-left-title > .site-meta-sub {
      font-size: 11px;
      color: var(--text-muted);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-weight: 500;
      margin-top: 2px;
    }

    .user-info-mobile {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
      margin-right: 12px;
    }

    @media (min-width: 1024px) {
      .user-info-mobile {
        display: none;
      }
    }

    .avatar-wrapper-m {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      border: 1.5px solid var(--primary);
      box-shadow: 0 0 8px var(--primary-glow);
      background-color: var(--surface-light);
      overflow: hidden;
    }

    .avatar-wrapper-m img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* BURGER MENU BUTTON */
    .burger-btn {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      border: 1px solid var(--surface-border);
      background: rgba(255, 255, 255, 0.02);
      cursor: pointer;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 5px;
      transition: all 0.3s;
    }

    .burger-btn:hover {
      border-color: var(--primary);
      background: var(--primary-glow);
    }

    .burger-btn span {
      display: block;
      width: 18px;
      height: 2px;
      background-color: var(--text);
      border-radius: 2px;
      transition: transform 0.3s, opacity 0.3s;
    }

    .burger-btn.active span:nth-child(1) {
      transform: translateY(7px) rotate(45deg);
    }
    .burger-btn.active span:nth-child(2) {
      opacity: 0;
    }
    .burger-btn.active span:nth-child(3) {
      transform: translateY(-7px) rotate(-45deg);
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

  <!-- AMBIENT COLOR GLOWS -->
  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>

  <!-- CORE DEVICE LAYOUT -->
  <div class="app-layout">

    <!-- SIDEBAR PARTIAL INCLUDE -->
    @include('user.layouts.partials.sidebar')

    <!-- MAIN RIGHT VIEWPORT -->
    <div class="main-wrapper">

      <!-- HEADER NAVBAR -->
      <header>
        <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
          <div class="avatar-wrapper-m" style="flex-shrink: 0;">
            <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://api.dicebear.com/7.x/bottts/svg?seed=' . Auth::user()->code }}" alt="{{ Auth::user()->name }}">
          </div>
          <div class="header-left-title" style="margin-right: 0;">
            <span>{{ Auth::user()->name }} <span style="color: var(--primary);">({{ Auth::user()->code }})</span></span>
            <span class="site-meta-sub">{{ Auth::user()->site->site_name ?? 'Not Assigned' }} - {{ Auth::user()->site->site_code ?? '***' }}</span>
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
        @yield('content')
      </main>

      <!-- BOTTOM APP NAV FOR MOBILE -->
      <nav class="bottom-nav">
        <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
          <span>Console</span>
        </a>
        <a href="{{ route('user.sendmoney') }}" class="nav-item {{ request()->routeIs('user.sendmoney') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
          <span>Transfer</span>
        </a>
        <a href="{{ route('user.addmoney') }}" class="nav-item {{ request()->routeIs('user.addmoney') ? 'active' : '' }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          <span>Request</span>
        </a>
        <a href="#" class="nav-item" onclick="toggleSidebar();">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
          <span>Menu</span>
        </a>
      </nav>

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
    // Sidebar Hamburger Navigation
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
  </script>
  @stack('scripts')
</body>
</html>
