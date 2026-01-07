<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - EZ NIRMAN</title>
  <link rel="icon" href="{{ asset('assets/images/logo.gif') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('assets/frontend/login-style.css') }}">
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

  </style>
</head>

<body>
  <div id="particles"></div>
  <div class="gradient-bg"></div>
  <div class="gradient-overlay"></div>
  <div class="mesh-grid"></div>

  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>

  <div class="content">
    <!-- Back to Home -->
    <a href="index.html" class="back-link">
      <span>←</span> Back to Home
    </a>

    <!-- Login Container -->
    <div class="login-container">
      <div class="login-card">
        <!-- Logo Section -->
        <div class="login-header">
          <div class="logo-wrapper">
            <img src="{{ asset('assets/images/logo.gif') }}" width="80" alt="EZ NIRMAN Logo">
          </div>
          <h1 class="login-title">Admin Portal</h1>
          <p class="login-subtitle">Access the EZ NIRMAN Administration Dashboard</p>
          <div class="security-badge">
            <span class="shield-icon">🛡️</span>
            <span>Secure Access</span>
          </div>
        </div>

        <!-- Login Form -->
        <form class="login-form" id="adminLoginForm" action="{{ route('admin.login.verify') }}" method="POST">
          @csrf

          <div class="form-group">
            <label for="adminEmail" class="form-label">
              <span class="label-icon">📧</span>
              Admin Email
            </label>
            <input 
              type="email" 
              id="adminEmail" 
              name="email" 
              class="form-input" 
              placeholder="Enter Email"
              required
              autocomplete="email"
            >
          </div>

          <div class="form-group">
            <label for="adminPassword" class="form-label">
              <span class="label-icon">🔐</span>
              Password
            </label>
            <div class="password-wrapper">
              <input 
                type="password" 
                id="adminPassword" 
                name="password" 
                class="form-input" 
                placeholder="Enter Password"
                required
                autocomplete="current-password"
              >
              <button type="button" class="toggle-password" onclick="togglePassword('adminPassword')">
                👁️
              </button>
            </div>
          </div>

          <div class="form-options">
            <label class="remember-me">
              <input type="checkbox" name="remember">
              <span>Remember me</span>
            </label>
          </div>

          <button type="submit" class="btn-login">
            <span class="btn-text">Access Dashboard</span>
            <span class="btn-icon">→</span>
          </button>

          <div class="login-footer">
            <p class="switch-portal">
              Not an admin? 
              <a href="user-login.html" class="portal-link">User Portal →</a>
            </p>
          </div>
        </form>

        <!-- Security Info -->
        <div class="security-info">
          <div class="info-item">
            <span class="info-icon">🔒</span>
            <span>256-bit Encryption</span>
          </div>
          <div class="info-item">
            <span class="info-icon">⚡</span>
            <span>2FA Protected</span>
          </div>
          <div class="info-item">
            <span class="info-icon">📊</span>
            <span>Activity Logged</span>
          </div>
        </div>
      </div>

      <!-- Side Info Panel -->
      <div class="info-panel">
        <div class="panel-content">
          <div class="panel-icon">🏗️</div>
          <h2 class="panel-title">Admin Control Center</h2>
          <p class="panel-description">
            Full system access with real-time monitoring, user management, 
            project oversight, and comprehensive analytics.
          </p>
          <div class="panel-features">
            <div class="panel-feature">
              <span class="feature-check">✓</span>
              <span>Complete System Control</span>
            </div>
            <div class="panel-feature">
              <span class="feature-check">✓</span>
              <span>User & Role Management</span>
            </div>
            <div class="panel-feature">
              <span class="feature-check">✓</span>
              <span>Advanced Analytics</span>
            </div>
            <div class="panel-feature">
              <span class="feature-check">✓</span>
              <span>Security & Audit Logs</span>
            </div>
            <div class="panel-feature">
              <span class="feature-check">✓</span>
              <span>Cash Flow Management</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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


  <script>
    // Particle System
    const particlesContainer = document.getElementById('particles');
    const particleCount = 40;

    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement('div');
      particle.className = 'particle';
      const size = Math.random() * 4 + 2;
      particle.style.width = `${size}px`;
      particle.style.height = `${size}px`;
      particle.style.left = `${Math.random() * 100}%`;
      particle.style.top = `${Math.random() * 100}%`;
      particle.style.animationDuration = `${Math.random() * 5 + 10}s`;
      particlesContainer.appendChild(particle);
    }

    // Toggle Password Visibility
    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const button = input.nextElementSibling;
      
      if (input.type === 'password') {
        input.type = 'text';
        button.textContent = '🙈';
      } else {
        input.type = 'password';
        button.textContent = '👁️';
      }
    }


    // Input Focus Effects
    const inputs = document.querySelectorAll('.form-input');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        if (!this.value) {
          this.parentElement.classList.remove('focused');
        }
      });
    });
  </script>
</body>

</html>