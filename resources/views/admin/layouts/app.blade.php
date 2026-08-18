<!DOCTYPE html>
<html lang="en">
<head>
  <title>@yield('title', 'Admin Dashboard') - EZ NIRMAN</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
  
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  
  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
  
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
  
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
  <style>
    #alert-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.alert {
    min-width: 300px;
    padding: 14px 18px;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 8px 20px rgba(0,0,0,.15);
    animation: slideIn .4s ease;
}

.alert-success {
    background: linear-gradient(135deg, #28a745, #218838);
}

.alert-error {
    background: linear-gradient(135deg, #dc3545, #b02a37);
}

.alert-close {
    background: none;
    border: none;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Select2 Custom Styling to match theme */
.select2-container--default .select2-selection--single {
    height: 40px !important;
    border: 1px solid #ced4da !important;
    border-radius: 6px !important;
    padding-top: 5px !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057 !important;
    line-height: 28px !important;
}

/* Custom Pagination Styling */
.custom-pagination {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
}
.custom-pagination .page-input-group {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #f8f9fa;
    padding: 5px 15px;
    border-radius: 30px;
    border: 1px solid #dee2e6;
}
.custom-pagination input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 2px 5px;
}
.custom-pagination .btn-nav {
    padding: 8px 20px;
    border-radius: 30px;
    border: none;
    background: #007bff;
    color: white;
    font-weight: 600;
    transition: all 0.3s;
}
.custom-pagination .btn-nav:hover {
    background: #0056b3;
    transform: translateY(-2px);
}
.custom-pagination .btn-nav:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}
  </style>
  @stack('styles')
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- Pre-loader -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  <!-- Sidebar -->
  @include('admin.layouts.partials.sidebar')

  <!-- Header -->
  <header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <!-- ======= Menu collapse Icon ===== -->
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="dropdown pc-h-item d-inline-flex d-md-none">
            <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" aria-expanded="false">
              <i class="ti ti-search"></i>
            </a>
            <div class="dropdown-menu pc-h-dropdown drp-search">
              <form class="px-3">
                <div class="form-group mb-0 d-flex align-items-center">
                  <i data-feather="search"></i>
                  <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
                </div>
              </form>
            </div>
          </li>
          <li class="pc-h-item d-none d-md-inline-flex">
            <form class="header-search">
              <i data-feather="search" class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Search here. . .">
            </form>
          </li>
        </ul>
      </div>
      <!-- [Mobile Media Block end] -->
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="dropdown pc-h-item header-user-profile">
            @php
              $isAdmin = Auth::guard('admin')->check();
              $authUser = $isAdmin ? Auth::guard('admin')->user() : Auth::guard('web')->user();
              $logoutRoute = $isAdmin ? route(getRoutePrefix() . 'logout') : route('coordinator.logout');
              $roleName = $isAdmin ? 'Administrator' : 'Coordinator';
            @endphp
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
              <img src="{{ asset('logo_with_bg.png') }}" alt="user-image" class="user-avtar">
              <span>{{ $authUser?->name ?? 'Admin Panel' }}</span>
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header">
                <div class="d-flex mb-1">
                  <div class="flex-shrink-0">
                    <img src="{{ asset('logo_with_bg.png') }}" alt="user-image" class="user-avtar wid-35" style="border-radius: 5px;">
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">{{ $authUser?->name ?? 'Admin Panel' }}</h6>
                    <span>{{ $roleName }}</span>
                  </div>
                  <a href="{{ $logoutRoute }}" class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
                </div>
              </div>
              <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="drp-t1" data-bs-toggle="tab" data-bs-target="#drp-tab-1"
                    type="button" role="tab" aria-controls="drp-tab-1" aria-selected="true"><i class="ti ti-user"></i>
                    Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="drp-t2" data-bs-toggle="tab" data-bs-target="#drp-tab-2" type="button"
                    role="tab" aria-controls="drp-tab-2" aria-selected="false"><i class="ti ti-phone"></i>
                    Help?</button>
                </li>
              </ul>
              <div class="tab-content" id="mysrpTabContent">
                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1"
                  tabindex="0">
                  <a href="{{ route(getRoutePrefix() . 'profile.index') }}" class="dropdown-item">
                    <i class="ti ti-user"></i>
                    <span>Account Settings</span>
                  </a>
                </div>
                <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2" tabindex="0">
                  <a href="tel:+916292237205" class="dropdown-item">
                    <i class="ti ti-phone"></i>
                    <span>Call Support</span>
                  </a>
                  <a href="https://wa.me/+916292237205?text=Hey%20*Saklin*,%20I%20need%20help%20in%20EZ%20Nirman%20Software" class="dropdown-item" target="_blank">
                    <i class="ti ti-messages"></i>
                    <span>Chat Support</span>
                  </a>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="pc-container">
    <div class="pc-content">
      @yield('content')
    </div>
  </div>

  <!-- Footer -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col-sm my-1">
          <p class="m-0">{{ date('Y') }} &#9829; EZ Nirman.</p>
        </div>
      </div>
    </div>
  </footer>




@if (session('success') || session('error') || $errors->any())
<div id="alert-container">

    @if (session('success'))
        <div class="alert alert-success">
            <span>{{ session('success') }}</span>
            <button class="alert-close">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            <span>{{ session('error') }}</span>
            <button class="alert-close">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <span>{{ $errors->first() }}</span>
            <button class="alert-close">&times;</button>
        </div>
    @endif

</div>
@endif


  <!-- Required JS -->
  <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('assets/js/pcoded.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

  <!-- jQuery & Select2 JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
        $('.select2').each(function() {
            $(this).select2({
                width: '100%',
                placeholder: $(this).data('placeholder'),
                allowClear: true
            });
        });
    });
  </script>

  <script>
    layout_change('light');
    change_box_container('false');
    layout_rtl_change('false');
    preset_change("preset-1");
    font_change("Public-Sans");
  </script>


<script>
  document.addEventListener('DOMContentLoaded', () => {

    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(alert => {
        // Auto hide after 4 seconds
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(30px)';
            setTimeout(() => alert.remove(), 400);
        }, 4000);

        // Manual close
        alert.querySelector('.alert-close').addEventListener('click', () => {
            alert.remove();
        });
    });

});

</script>


  @stack('scripts')
</body>
</html>