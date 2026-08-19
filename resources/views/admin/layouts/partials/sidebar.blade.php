<style>
  /* Sidebar Section Custom Colors */
  .pc-sidebar .pc-item.pc-caption.theme-asset label { color: #6610f2 !important; }
  .pc-sidebar .theme-asset.active > .pc-link { color: #6610f2 !important; }
  .pc-sidebar .theme-asset.active > .pc-link .pc-micon { color: #6610f2 !important; }
  .pc-sidebar .theme-asset .pc-submenu .pc-item.active > .pc-link { color: #6610f2 !important; }
  .pc-sidebar .theme-asset .pc-submenu .pc-item.active > .pc-link:before { background-color: #6610f2 !important; }

  .pc-sidebar .pc-item.pc-caption.theme-pettycash label { color: #198754 !important; }
  .pc-sidebar .theme-pettycash.active > .pc-link { color: #198754 !important; }
  .pc-sidebar .theme-pettycash.active > .pc-link .pc-micon { color: #198754 !important; }
  .pc-sidebar .theme-pettycash .pc-submenu .pc-item.active > .pc-link { color: #198754 !important; }
  .pc-sidebar .theme-pettycash .pc-submenu .pc-item.active > .pc-link:before { background-color: #198754 !important; }

  .pc-sidebar .pc-item.pc-caption.theme-site label { color: #20c997 !important; }
  .pc-sidebar .theme-site.active > .pc-link { color: #20c997 !important; }
  .pc-sidebar .theme-site.active > .pc-link .pc-micon { color: #20c997 !important; }
  .pc-sidebar .theme-site .pc-submenu .pc-item.active > .pc-link { color: #20c997 !important; }
  .pc-sidebar .theme-site .pc-submenu .pc-item.active > .pc-link:before { background-color: #20c997 !important; }

  .pc-sidebar .pc-item.pc-caption.theme-material label { color: #fd7e14 !important; }
  .pc-sidebar .theme-material.active > .pc-link { color: #fd7e14 !important; }
  .pc-sidebar .theme-material.active > .pc-link .pc-micon { color: #fd7e14 !important; }
  .pc-sidebar .theme-material .pc-submenu .pc-item.active > .pc-link { color: #fd7e14 !important; }
  .pc-sidebar .theme-material .pc-submenu .pc-item.active > .pc-link:before { background-color: #fd7e14 !important; }

  .pc-sidebar .pc-item.pc-caption.theme-purchase label { color: #d63384 !important; }
  .pc-sidebar .theme-purchase.active > .pc-link { color: #d63384 !important; }
  .pc-sidebar .theme-purchase.active > .pc-link .pc-micon { color: #d63384 !important; }
  .pc-sidebar .theme-purchase .pc-submenu .pc-item.active > .pc-link { color: #d63384 !important; }
  .pc-sidebar .theme-purchase .pc-submenu .pc-item.active > .pc-link:before { background-color: #d63384 !important; }

  .pc-sidebar .pc-item.pc-caption.theme-hr label { color: #0d6efd !important; }
  .pc-sidebar .theme-hr.active > .pc-link { color: #0d6efd !important; }
  .pc-sidebar .theme-hr.active > .pc-link .pc-micon { color: #0d6efd !important; }
  .pc-sidebar .theme-hr .pc-submenu .pc-item.active > .pc-link { color: #0d6efd !important; }
  .pc-sidebar .theme-hr .pc-submenu .pc-item.active > .pc-link:before { background-color: #0d6efd !important; }

  .pc-sidebar .pc-item.pc-caption.theme-settings label { color: #17a2b8 !important; }
  .pc-sidebar .theme-settings.active > .pc-link { color: #17a2b8 !important; }
  .pc-sidebar .theme-settings.active > .pc-link .pc-micon { color: #17a2b8 !important; }
  .pc-sidebar .theme-settings .pc-submenu .pc-item.active > .pc-link { color: #17a2b8 !important; }
  .pc-sidebar .theme-settings .pc-submenu .pc-item.active > .pc-link:before { background-color: #17a2b8 !important; }
</style>

<nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="{{ route(getRoutePrefix() . 'dashboard') }}" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <img src="{{ asset('logo_with_bg.png') }}" class="img-fluid" width="50" alt="logo" style="border-radius: 5px;">
          <span class="b-title fw-bold text-decoration-none text-dark fs-4">EZ NIRMAN</span>
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item {{ Route::is(getRoutePrefix() . 'dashboard') ? 'active' : '' }}">
            <a href="{{ route(getRoutePrefix() . 'dashboard') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>


          <!-- Petty Cash Management (Coordinator) -->
          @if(getRoutePrefix() == 'coordinator.')
          <li class="pc-item pc-caption theme-pettycash">
            <label>Petty Cash Management</label>
            <i class="ti ti-cash"></i>
          </li>
          <li class="pc-item pc-hasmenu theme-pettycash {{ Route::is('coordinator.accountcode.*') || Route::is('coordinator.pettycash.*') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-currency-rupee"></i></span><span
                class="pc-mtext">Petty Cash</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is('coordinator.accountcode.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route('coordinator.accountcode.index') }}">A/C Code</a>
              </li>
              @php
                  $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
                  $coordinator = \App\Models\Coordinator::where('user_id', $user->id)->first();
                  $assignedSitesIds = $coordinator ? $coordinator->assigned_sites_ids : [];
                  $assignedSites = \App\Models\WorkingSite::whereIn('id', $assignedSitesIds ?? [])->get();
              @endphp
              @foreach($assignedSites as $site)
                <li class="pc-item {{ request()->is('coordinator/pettycash/site/'.$site->id) ? 'active' : '' }}">
                  <a class="pc-link" href="{{ url('coordinator/pettycash/site/'.$site->id) }}">{{ $site->name }}</a>
                </li>
              @endforeach
            </ul>
          </li>
          @endif

          <!-- Asset Management -->
          <li class="pc-item pc-caption theme-asset">
            <label>Asset Management</label>
            <i class="ti ti-dashboard"></i>
          </li>
          <li class="pc-item pc-hasmenu theme-asset {{ Route::is(getRoutePrefix() . 'machinery.machine-category') || Route::is(getRoutePrefix() . 'machinery.add-machinery') || Route::is(getRoutePrefix() . 'machinery.transfer-machinery') || Request::is(getRouteUrlPrefix() . 'machinery/running*') || Request::is(getRouteUrlPrefix() . 'machinery/repair*') || Request::is(getRouteUrlPrefix() . 'machinery/damaged*') || Request::is(getRouteUrlPrefix() . 'machinery/missing*') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-tools"></i></span><span
                class="pc-mtext">Machinery & Tools</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.machine-category') ? 'active' : '' }}">
                <a href="{{ route(getRoutePrefix() . 'machinery.machine-category') }}" class="pc-link">Machine Category</a>
              </li>
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.add-machinery') ? 'active' : '' }}">
                <a href="{{ route(getRoutePrefix() . 'machinery.add-machinery') }}" class="pc-link">Add Machinery</a>
              </li>
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.transfer-machinery') ? 'active' : '' }}">
                <a href="{{ route(getRoutePrefix() . 'machinery.transfer-machinery') }}" class="pc-link">Transferred Machinery</a>
              </li>
              <li class="pc-item pc-hasmenu {{ Request::is(getRouteUrlPrefix() . 'machinery/running*') || Request::is(getRouteUrlPrefix() . 'machinery/repair*') || Request::is(getRouteUrlPrefix() . 'machinery/damaged*') || Request::is(getRouteUrlPrefix() . 'machinery/missing*') ? 'active pc-trigger' : '' }}">
                <a href="#!" class="pc-link">Machine Condition<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="pc-submenu">
                  <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.running*') ? 'active' : '' }}">
                    <a class="pc-link" href="{{ route(getRoutePrefix() . 'machinery.running') }}">Running</a>
                  </li>
                  <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.repair*') ? 'active' : '' }}">
                    <a class="pc-link" href="{{ route(getRoutePrefix() . 'machinery.repair') }}">Repairing</a>
                  </li>
                  <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.damaged*') ? 'active' : '' }}">
                    <a class="pc-link" href="{{ route(getRoutePrefix() . 'machinery.damaged') }}">Damage</a>
                  </li>
                  <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.missing*') ? 'active' : '' }}">
                    <a class="pc-link" href="{{ route(getRoutePrefix() . 'machinery.missing') }}">Missing</a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>


          <!-- Working Sites -->
          @if(\Illuminate\Support\Facades\Auth::guard('admin')->check())
          <li class="pc-item pc-caption theme-site">
            <label>Site Management</label>
            <i class="ti ti-dashboard"></i>
          </li>
          <li class="pc-item pc-hasmenu theme-site {{ Route::is(getRoutePrefix() . 'machinery.working-sites') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-building"></i></span><span
                class="pc-mtext">Working Sites</span><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'machinery.working-sites') ? 'active' : '' }}"><a class="pc-link"
                  href="{{ route(getRoutePrefix() . 'machinery.working-sites') }}">Manage Sites</a></li>
            </ul>
          </li>
          @endif


          <!-- Material Management -->
          <li class="pc-item pc-caption theme-material">
            <label>Material Management</label>
            <i class="ti ti-box"></i>
          </li>
          <li class="pc-item pc-hasmenu theme-material {{ Route::is(getRoutePrefix() . 'purchase.units.*') || Route::is(getRoutePrefix() . 'purchase.product-categories.*') || Route::is(getRoutePrefix() . 'purchase.material-codes.*') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-stack"></i></span><span
                class="pc-mtext">Material Management</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'purchase.units.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route(getRoutePrefix() . 'purchase.units.index') }}">Units</a>
              </li>
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'purchase.product-categories.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route(getRoutePrefix() . 'purchase.product-categories.index') }}">Material Category</a>
              </li>
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'purchase.material-codes.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route(getRoutePrefix() . 'purchase.material-codes.index') }}">Material Code</a>
              </li>
            </ul>
          </li>

          <!-- Purchase Register -->
          <li class="pc-item pc-caption theme-purchase">
            <label>Purchase Register</label>
            <i class="ti ti-brand-chrome"></i>
          </li>
          <li class="pc-item pc-hasmenu theme-purchase {{ Route::is(getRoutePrefix() . 'purchase.material-purchases.*') || Route::is(getRoutePrefix() . 'purchase.material-consumes.*') || Route::is(getRoutePrefix() . 'purchase.material-wastage') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="ti ti-shopping-cart"></i></span><span
                class="pc-mtext">Material Purchase</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'purchase.material-purchases.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route(getRoutePrefix() . 'purchase.material-purchases.index') }}">Material Purchase</a>
              </li>
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'purchase.material-consumes.*') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route(getRoutePrefix() . 'purchase.material-consumes.index') }}">Material Consume</a>
              </li>
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'purchase.material-wastage') ? 'active' : '' }}">
                <a class="pc-link" href="{{ route(getRoutePrefix() . 'purchase.material-wastage') }}">Material Wastage</a>
              </li>
            </ul>
          </li>


          <!-- Human Resources -->
          <li class="pc-item pc-caption theme-hr">
            <label>HR Management</label>
            <i class="ti ti-users"></i>
          </li>
          <li class="pc-item pc-hasmenu theme-hr {{ Route::is(getRoutePrefix() . 'hrmanagement.designations.*') || Route::is(getRoutePrefix() . 'hrmanagement.skills.*') || Request::is(getRouteUrlPrefix() . 'hrmanagement') || Request::is(getRouteUrlPrefix() . 'hrmanagement/create*') || Request::is(getRouteUrlPrefix() . 'hrmanagement/*/edit*') || Request::is(getRouteUrlPrefix() . 'hrmanagement/*/show*') ? 'active pc-trigger' : '' }}">
            <a href="#!" class="pc-link"><span class="pc-micon"><i class="fas fa-users-cog"></i></span><span
                class="pc-mtext">HR Management</span><span class="pc-arrow"><i
                  data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'hrmanagement.designations.*') ? 'active' : '' }}">
                <a href="{{ route(getRoutePrefix() . 'hrmanagement.designations.index') }}" class="pc-link">Designations</a>
              </li>
              <li class="pc-item {{ Route::is(getRoutePrefix() . 'hrmanagement.skills.*') ? 'active' : '' }}">
                <a href="{{ route(getRoutePrefix() . 'hrmanagement.skills.index') }}" class="pc-link">Skills</a>
              </li>
              <li class="pc-item pc-hasmenu {{ Request::is(getRouteUrlPrefix() . 'hrmanagement') || Request::is(getRouteUrlPrefix() . 'hrmanagement/create*') || Request::is(getRouteUrlPrefix() . 'hrmanagement/*/edit*') || Request::is(getRouteUrlPrefix() . 'hrmanagement/*/show*') ? 'active pc-trigger' : '' }}">
                <a href="#!" class="pc-link">Human Resource<span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                <ul class="pc-submenu">
                  <li class="pc-item {{ request('role') == 'worker' ? 'active' : '' }}"><a class="pc-link" href="{{ route(getRoutePrefix() . 'hrmanagement.index', ['role' => 'worker']) }}">Add Workers</a></li>
                  <li class="pc-item {{ request('role') == 'supervisor' ? 'active' : '' }}"><a class="pc-link" href="{{ route(getRoutePrefix() . 'hrmanagement.index', ['role' => 'supervisor']) }}">Add Supervisors</a></li>
                  <li class="pc-item {{ request('role') == 'staff' ? 'active' : '' }}"><a class="pc-link" href="{{ route(getRoutePrefix() . 'hrmanagement.index', ['role' => 'staff']) }}">Add Staffs</a></li>
                  <li class="pc-item {{ request('role') == 'hr' ? 'active' : '' }}"><a class="pc-link" href="{{ route(getRoutePrefix() . 'hrmanagement.index', ['role' => 'hr']) }}">Add HRs</a></li>
                </ul>
              </li>
            </ul>
          </li>

          <!-- Settings -->
          <li class="pc-item pc-caption theme-settings">
            <label>System Settings</label>
            <i class="ti ti-settings"></i>
          </li>
          <li class="pc-item theme-settings {{ Route::is(getRoutePrefix() . 'profile.*') ? 'active' : '' }}">
            <a href="{{ route(getRoutePrefix() . 'profile.index') }}" class="pc-link">
              <span class="pc-micon"><i class="ti ti-settings"></i></span>
              <span class="pc-mtext">Account Settings</span>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </nav>