<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ NIRMAN - Construction Enterprise Management</title>
    <link rel="icon" href="{{ asset('assets/images/logo.gif') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/frontend/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="cinematic-bg"></div>
    <div class="cinematic-overlay"></div>

    <div class="content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo-wrapper" style="display: flex; align-items: center; gap: 1rem;">
                    <img src="{{ asset('assets/images/logo.gif') }}" width="55" alt="EZ NIRMAN">
                    <div class="brand-info">
                        <div class="brand-title">EZ NIRMAN</div>
                        <div class="brand-subtitle">Strategic Construction Portal</div>
                    </div>
                </div>
                <div class="nav-status glass-panel" style="padding: 0.6rem 1.2rem; border-radius: 50px;">
                    <span class="status-text" style="color: var(--primary); font-weight: 800;">✓ INFRASTRUCTURE ACTIVE</span>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero">
            <h1 class="hero-title">Precision <br>Engineering</h1>
            <p class="hero-description">
                Unifying fleet intelligence, project forensics, and financial mastery into a single glass-pane command center for modern construction empires.
            </p>
            <div class="hero-cta">
                <a href="{{ route('admin.login') }}" class="btn-primary">ADMIN COMMAND</a>
                <a href="{{ route('login') }}" class="btn-secondary">REVIEW PLANS</a>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card glass-panel">
                    <div class="stat-number">1.5K+</div>
                    <div class="stat-label">MEGA PROJECTS</div>
                </div>
                <div class="stat-card glass-panel">
                    <div class="stat-number">850+</div>
                    <div class="stat-label">HEAVY ASSETS</div>
                </div>
                <div class="stat-card glass-panel">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">PRECISION RATE</div>
                </div>
                <div class="stat-card glass-panel">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">OPERATIONAL OPS</div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section" id="features">
            <div class="section-header">
                <h2 class="section-title">Institutional Grade Features</h2>
            </div>

            <div class="features-grid">
                <div class="feature-card glass-panel">
                    <div class="feature-icon-wrapper">🚜</div>
                    <h3 class="feature-title">Fleet Intelligence</h3>
                    <p class="feature-description">
                        Real-time telemetry and lifecycle management for your heavy machinery ecosystem.
                    </p>
                </div>

                <div class="feature-card glass-panel">
                    <div class="feature-icon-wrapper">📐</div>
                    <h3 class="feature-title">Site Forensics</h3>
                    <p class="feature-description">
                        Digital twin site oversight with milestone tracking and high-fidelity project reporting.
                    </p>
                </div>

                <div class="feature-card glass-panel">
                    <div class="feature-icon-wrapper">💹</div>
                    <h3 class="feature-title">Financial Matrix</h3>
                    <p class="feature-description">
                        Strategic cash flow auditing, procurement logic, and enterprise budget governance.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <p>&copy; {{ date('Y') }} EZ NIRMAN | Strategic Construction Enterprise | All Rights Reserved.</p>
                <p style="margin-top: 1rem; font-size: 0.8rem; opacity: 0.5;">ISO 9001:2015 CERTIFIED SYSTEM | SECURE ARCHITECTURE</p>
            </div>
        </footer>
    </div>
</body>
</html>