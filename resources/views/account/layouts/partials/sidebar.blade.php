  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="{{ route('account.dashboard') }}" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <img src="{{ asset('assets/images/logo.gif') }}" class="img-fluid" width="70" alt="logo">
          <span class="b-title fw-bold text-decoration-none text-dark fs-4">EZ NIRMAN</span>
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item {{ Route::is('account.dashboard') ? 'active' : '' }}">
            <a href="{{ route('account.dashboard') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>

          <!-- Petty Cash Management -->
          <li class="pc-item pc-caption">
            <label>Petty Cash Management</label>
            <i class="ti ti-brand-chrome"></i>
          </li>
          <li class="pc-item {{ Route::is('account.accountcode.*') ? 'active' : '' }}">
            <a href="{{ route('account.accountcode.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-barcode"></i></span>
              <span class="pc-mtext">A/C Code</span>
            </a>
          </li>
          <li class="pc-item pc-hasmenu">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-currency-rupee"></i></span><span
                class="pc-mtext">Petty Cash</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is('account.cashmanagement.index') ? 'active' : '' }}"><a class="pc-link" href="{{ route('account.cashmanagement.index') }}">Cash Management</a></li>
              <li class="pc-item {{ Route::is('account.cashmanagement.send') ? 'active' : '' }}"><a class="pc-link" href="{{ route('account.cashmanagement.send') }}">Make Payments</a></li>
            </ul>
          </li>

          <!-- Purchase Management -->
          <li class="pc-item pc-caption">
            <label>Purchase Management</label>
            <i class="ti ti-brand-chrome"></i>
          </li>
          <li class="pc-item pc-hasmenu">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-shopping-cart"></i></span><span
                class="pc-mtext">Purchase Register</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is('account.purchase.unauthorized-purchases.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('account.purchase.unauthorized-purchases.index') }}">Unauthorized Purchase</a>
              </li>
            </ul>
          </li>

          <!-- <li class="pc-item">
            <a href="" class="pc-link">
              <span class="pc-micon"><i class="ti ti-key"></i></span>
              <span class="pc-mtext">Petty User Login</span>
            </a>
          </li> -->

          <!-- Settings -->
          <li class="pc-item pc-caption">
            <label>System Settings</label>
            <i class="ti ti-settings"></i>
          </li>
          <li class="pc-item {{ Route::is('account.profile.*') ? 'active' : '' }}">
            <a href="{{ route('account.profile.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-settings"></i></span>
              <span class="pc-mtext">Account Settings</span>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>