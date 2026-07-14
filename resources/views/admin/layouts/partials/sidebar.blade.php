  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="{{ route('admin.dashboard') }}" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <img src="{{ asset('logo_with_bg.png') }}" class="img-fluid" width="50" alt="logo" style="border-radius: 5px;">
          <span class="b-title fw-bold text-decoration-none text-dark fs-4">EZ NIRMAN</span>
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>


          <!-- Machinery & Tools -->
          <li class="pc-item pc-caption">
            <label>Machinery & Tools</label>
            <i class="ti ti-dashboard"></i>
          </li>
          <li class="pc-item {{ Route::is('admin.machinery.machine-category') ? 'active' : '' }}">
            <a href="{{ route('admin.machinery.machine-category') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-list"></i></span>
              <span class="pc-mtext">Machine Category</span>
            </a>
          </li>
          <li class="pc-item {{ Route::is('admin.machinery.add-machinery') ? 'active' : '' }}">
            <a href="{{ route('admin.machinery.add-machinery') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-tools"></i></span>
              <span class="pc-mtext">Add Machinery</span>
            </a>
          </li>
          <li class="pc-item {{ Route::is('admin.machinery.transfer-machinery') ? 'active' : '' }}">
            <a href="{{ route('admin.machinery.transfer-machinery') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-share"></i></span>
              <span class="pc-mtext">Transferred Machinery</span>
            </a>
          </li>
          <!-- Machinery Condition-->
          <li class="pc-item pc-caption">
            <label>Machinery Condition</label>
            <i class="ti ti-dashboard"></i>
          </li>
          <li class="pc-item pc-hasmenu {{ Request::is('admin/machinery/running*') || Request::is('admin/machinery/repair*') || Request::is('admin/machinery/damaged*') || Request::is('admin/machinery/missing*') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-stack"></i></span><span
                class="pc-mtext">Machine Condition</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is('admin.machinery.running*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.machinery.running') }}">Running</a>
              </li>
              <li class="pc-item {{ Route::is('admin.machinery.repair*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.machinery.repair') }}">Repairing</a>
              </li>
              <li class="pc-item {{ Route::is('admin.machinery.damaged*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.machinery.damaged') }}">Damage</a>
              </li>
              <li class="pc-item {{ Route::is('admin.machinery.missing*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.machinery.missing') }}">Missing</a>
              </li>
            </ul>
          </li>


          <!-- Working Sites -->
          <li class="pc-item pc-caption">
            <label>Site Management</label>
            <i class="ti ti-news"></i>
          </li>
          <li class="pc-item pc-hasmenu {{ Route::is('admin.machinery.working-sites') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-building-skyscraper"></i></span><span
                class="pc-mtext">Working Sites</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item"><a class="pc-link" href="{{ route('admin.machinery.working-sites') }}">Manage Sites</a></li>
            </ul>
          </li>


          <!-- Purchase Register -->
          <li class="pc-item pc-caption">
            <label>Purchase Register</label>
            <i class="ti ti-brand-chrome"></i>
          </li>
          <li class="pc-item {{ Route::is('admin.purchase.units.*') ? 'active' : '' }}">
            <a href="{{ route('admin.purchase.units.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-layout"></i></span>
              <span class="pc-mtext">Units</span>
            </a>
          </li>
          <li class="pc-item pc-hasmenu {{ Request::is('admin/purchase/product-categories*') || Request::is('admin/purchase/material-codes*') || Request::is('admin/purchase/material-purchases*') || Request::is('admin/purchase/material-consumes*') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-stack"></i></span><span
                class="pc-mtext">Material Management</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is('admin.purchase.product-categories.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.purchase.product-categories.index') }}">Material Category</a>
              </li>
              <li class="pc-item {{ Route::is('admin.purchase.material-codes.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.purchase.material-codes.index') }}">Material Code</a>
              </li>
              <li class="pc-item {{ Route::is('admin.purchase.material-purchases.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.purchase.material-purchases.index') }}">Material Purchase</a>
              </li>
              <!-- <li class="pc-item {{ Route::is('admin.purchase.unauthorized-purchases.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.purchase.unauthorized-purchases.index') }}">Unauthorized Purchase</a>
              </li> -->
              <li class="pc-item {{ Route::is('admin.purchase.material-consumes.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('admin.purchase.material-consumes.index') }}">Material Consume</a>
              </li>
            </ul>
          </li>


          <!-- Human Resources -->
          <li class="pc-item pc-caption">
            <label>HR Management</label>
            <i class="ti ti-brand-chrome"></i>
          </li>
          <li class="pc-item {{ Route::is('admin.hrmanagement.designations.*') ? 'active' : '' }}">
            <a href="{{ route('admin.hrmanagement.designations.index') }}" class="pc-link">
              <span class="pc-micon"><i class="fas fa-graduation-cap"></i></span>
              <span class="pc-mtext">Designations</span>
            </a>
          </li>
          <li class="pc-item {{ Route::is('admin.hrmanagement.skills.*') ? 'active' : '' }}">
            <a href="{{ route('admin.hrmanagement.skills.index') }}" class="pc-link">
              <span class="pc-micon"><i class="fas fa-chart-line"></i></span>
              <span class="pc-mtext">Skills</span>
            </a>
          </li>
          <li class="pc-item pc-hasmenu {{ Request::is('admin/hrmanagement') || Request::is('admin/hrmanagement/create*') || Request::is('admin/hrmanagement/*/edit*') || Request::is('admin/hrmanagement/*/show*') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="fas fa-users"></i></span><span
                class="pc-mtext">Human Resource</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ request('role') == 'worker' ? 'active' : '' }}"><a class="pc-link" href="{{ route('admin.hrmanagement.index', ['role' => 'worker']) }}">Add Workers</a></li>
              <li class="pc-item {{ request('role') == 'supervisor' ? 'active' : '' }}"><a class="pc-link" href="{{ route('admin.hrmanagement.index', ['role' => 'supervisor']) }}">Add Supervisors</a></li>
              <li class="pc-item {{ request('role') == 'staff' ? 'active' : '' }}"><a class="pc-link" href="{{ route('admin.hrmanagement.index', ['role' => 'staff']) }}">Add Staffs</a></li>
              <li class="pc-item {{ request('role') == 'hr' ? 'active' : '' }}"><a class="pc-link" href="{{ route('admin.hrmanagement.index', ['role' => 'hr']) }}">Add HRs</a></li>
            </ul>
          </li>

          <!-- Settings -->
          <li class="pc-item pc-caption">
            <label>System Settings</label>
            <i class="ti ti-settings"></i>
          </li>
          <li class="pc-item {{ Route::is('admin.profile.*') ? 'active' : '' }}">
            <a href="{{ route('admin.profile.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-settings"></i></span>
              <span class="pc-mtext">Account Settings</span>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>