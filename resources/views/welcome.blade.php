<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EZ NIRMAN - Next Gen Admin Portal</title>
  <!-- [Favicon] icon -->
  <link rel="icon" href="assets/images/logo.gif" type="image/x-icon">
  <link rel="stylesheet" href="assets/frontend/style.css">
</head>

<body>
  <div class="custom-cursor"></div>
  <div class="custom-cursor-dot"></div>
  <div class="laser-scanner"></div>

  <div id="particles"></div>
  <div class="gradient-bg"></div>
  <div class="gradient-overlay"></div>
  <div class="mesh-grid"></div>
  <div class="blueprint-overlay"></div>
  <div class="blueprint-grid"></div>

  <!-- Technical Overlays -->
  <div class="tech-data" style="top: 20px; left: 20px;">LAT: 22.5726° N <br> LON: 88.3639° E</div>
  <div class="tech-data" style="top: 20px; right: 20px;">SYSTEM STATUS: OPTIMAL <br> UPTIME: 99.98%</div>
  <div class="tech-data" style="bottom: 20px; left: 20px;">CORE VERSION: 4.2.0-STABLE <br> ENCRYPTION: AES-256</div>
  
  <!-- Floating Elements -->
  <div class="structural-floating" style="top: 15%; left: 5%;">🏗️</div>
  <div class="structural-floating" style="top: 60%; right: 8%;">📐</div>
  <div class="structural-floating" style="bottom: 10%; left: 10%;">🚧</div>

  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="orb orb3"></div>

  <div class="content">
    <!-- Navbar -->
    <nav class="navbar">
      <div class="nav-container">
        <div class="logo-wrapper">
          <div class="logo-3d"><img src="{{ asset('assets/images/logo.gif') }}" width="80" alt="EZ NIRMAN"></div>
          <div class="brand-info">
            <div class="brand-title">EZ NIRMAN</div>
            <div class="brand-subtitle">Precision Engineering & Management</div>
          </div>
        </div>
        <div class="nav-status">
          <div class="status-dot"></div>
          <span class="status-text">Infrastructure Ready</span>
        </div>
      </div>
      <div class="caution-stripes"></div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-bg-image"></div>
      <div class="hero-badge">🏗️ Industrial Grade Excellence</div>
      <h1 class="hero-title" data-parallax="0.2">From Blueprint <br>To Reality</h1>
      <p class="hero-description" data-parallax="0.1">
        The ultimate ecosystem for modern construction empires. 
        Streamline operations, manage heavy machinery, and master your cash flow with institutional-grade precision.
      </p>
      <div class="hero-cta">
        <a href="{{ route('admin.login') }}" class="btn-primary">Admin Control Center</a>
        <a href="{{ route('login') }}" class="btn-secondary">User Dashboard</a>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-number" data-count="500">500+</div>
          <div class="stat-label">Mega Projects</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" data-count="99">99.9%</div>
          <div class="stat-label">System Stability</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" data-count="24">24/7</div>
          <div class="stat-label">On-Site Support</div>
        </div>
        <div class="stat-card">
          <div class="stat-number" data-count="150">150+</div>
          <div class="stat-label">Heavy Assets</div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
      <div class="section-header">
        <div class="section-subtitle">Core Infrastructure</div>
        <h2 class="section-title">Built for Performance</h2>
        <p class="section-description">
          Engineered to handle the complexities of large-scale construction and infrastructure development.
        </p>
      </div>

      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon-wrapper">🛠️</div>
          <h3 class="feature-title">Project Engineering</h3>
          <p class="feature-description">
            Holistic project lifecycle management with milestone tracking, resource allocation, and automated reporting.
          </p>
        </div>

        <div class="feature-card">
          <div class="feature-icon-wrapper">⚙️</div>
          <h3 class="feature-title">Asset Intelligence</h3>
          <p class="feature-description">
            Real-time tracking of heavy machinery, maintenance logs, and fuel efficiency analytics for maximum ROI.
          </p>
        </div>

        <div class="feature-card">
          <div class="feature-icon-wrapper">💹</div>
          <h3 class="feature-title">Financial Logic</h3>
          <p class="feature-description">
            Institutional-grade cash flow management, budget tracking, and automated procurement systems.
          </p>
        </div>

        <div class="feature-card">
          <div class="feature-icon-wrapper">🏗️</div>
          <h3 class="feature-title">Site Oversight</h3>
          <p class="feature-description">
            Digital site logs, safety compliance tracking, and multi-location management from a single glass pane.
          </p>
        </div>

        <div class="feature-card">
          <div class="feature-icon-wrapper">📂</div>
          <h3 class="feature-title">Blueprint Archive</h3>
          <p class="feature-description">
            Centralized document control for architectural drawings, permits, and contractual obligations.
          </p>
        </div>

        <div class="feature-card">
          <div class="feature-icon-wrapper">🛡️</div>
          <h3 class="feature-title">Risk Mitigation</h3>
          <p class="feature-description">
            Advanced audit logs, role-based security, and data encryption to protect your enterprise assets.
          </p>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="cta-container">
        <h2 class="cta-title">Start Building Your Legacy</h2>
        <p class="cta-description">
          Deploy the most advanced construction management platform and take command of your infrastructure.
        </p>
        <div class="cta-button">
          <a href="{{ route('admin.login') }}" class="btn-primary">Initialize Access</a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="caution-stripes" style="top: 0; bottom: auto;"></div>
      <div class="footer-content">
        <p>&copy; {{ date('Y') }} EZ NIRMAN. Engineered for Excellence | Ranihati Construction PVT LTD | All Rights Reserved.</p>
        <p style="margin-top: 10px; font-size: 0.8rem; opacity: 0.6;">ISO 9001:2015 Certified Management System</p>
      </div>
    </footer>
  </div>

  <script>
    // Custom Cursor Logic
    const cursor = document.querySelector('.custom-cursor');
    const cursorDot = document.querySelector('.custom-cursor-dot');

    document.addEventListener('mousemove', (e) => {
      cursor.style.transform = `translate(${e.clientX - 15}px, ${e.clientY - 15}px)`;
      cursorDot.style.transform = `translate(${e.clientX - 30}px, ${e.clientY - 30}px)`;
    });

    // Parallax Logic
    document.addEventListener('mousemove', (e) => {
      const parallaxElements = document.querySelectorAll('[data-parallax]');
      const x = (window.innerWidth - e.pageX * 2) / 100;
      const y = (window.innerHeight - e.pageY * 2) / 100;

      parallaxElements.forEach(el => {
        const speed = el.getAttribute('data-parallax');
        el.style.transform = `translateX(${x * speed}px) translateY(${y * speed}px)`;
      });
    });

    // Particle System
    const particlesContainer = document.getElementById('particles');
    const particleCount = 60;

    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement('div');
      particle.className = 'particle';
      const size = Math.random() * 3 + 1;
      particle.style.width = `${size}px`;
      particle.style.height = `${size}px`;
      particle.style.background = Math.random() > 0.5 ? '#ff8c00' : '#0066ff';
      particle.style.left = `${Math.random() * 100}%`;
      particle.style.top = `${Math.random() * 100}%`;
      particle.style.opacity = Math.random() * 0.5 + 0.2;
      particle.style.animationDuration = `${Math.random() * 5 + 10}s`;
      particlesContainer.appendChild(particle);
    }

    // Scroll Reveal Effect
    const revealElements = document.querySelectorAll('.feature-card, .stat-card, .section-header');
    const revealOnScroll = () => {
      revealElements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.85) {
          el.style.opacity = '1';
          el.style.transform = 'translateY(0)';
        }
      });
    };

    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', () => {
      revealElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
      });
      revealOnScroll();
    });
  </script>
</body>

</html>