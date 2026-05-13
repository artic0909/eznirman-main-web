<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ NIRMAN - Construction Enterprise Management</title>
    <link rel="icon" href="{{ asset('assets/images/logo.gif') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/frontend/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .section-header { margin-bottom: 3rem; }
        .feature-grid-v2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        .mini-feature {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        .mini-feature:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(10px);
        }
        .mini-icon {
            font-size: 2rem;
            color: var(--primary);
            background: rgba(255, 184, 0, 0.1);
            padding: 0.8rem;
            border-radius: 12px;
        }
        .mini-content h4 {
            color: #fff;
            margin-bottom: 0.3rem;
            font-size: 1.1rem;
        }
        .mini-content p {
            color: var(--text-dim);
            font-size: 0.9rem;
        }
        .visibility-layer {
            background: rgba(0, 0, 0, 0.4);
            padding: 4rem 0;
        }
        @media (max-width: 768px) {
            .nav-status { display: none !important; }
            .hero-title { font-size: 3.5rem !important; }
            .header-actions { gap: 0.5rem !important; }
            .header-actions .btn-primary, .header-actions .btn-secondary {
                padding: 0.5rem 1rem !important;
                font-size: 0.8rem !important;
            }
        }
        ::placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
            opacity: 1; /* Firefox */
        }
        :-ms-input-placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        ::-ms-input-placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
        }
    </style>
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
                        <div class="brand-subtitle">Ranihati Construction Private Limited</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <div class="nav-status glass-panel" style="padding: 0.6rem 1.2rem; border-radius: 50px;">
                        <span class="status-text" style="color: var(--primary); font-weight: 800;">✓ INFRASTRUCTURE ACTIVE</span>
                    </div>
                    <div class="header-actions" style="display: flex; gap: 1rem;">
                        <a href="{{ route('admin.login') }}" class="btn-secondary" style="padding: 0.6rem 1.5rem; font-size: 0.8rem; margin: 0; border-radius: 50px;">ADMIN LOGIN</a>
                        <a href="#" class="btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.8rem; margin: 0; border-radius: 50px; background: var(--primary); color: #000;">
                            <i class="fab fa-google-play" style="margin-right: 5px;"></i> GET APP
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero">
            <h1 class="hero-title">Precision <br>Engineering</h1>
            <p class="hero-description">
                Unifying fleet intelligence, site logistics, and financial forensics into a single glass-pane command center for modern construction empires.
            </p>
            <div class="hero-cta" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="#" class="btn-primary" style="background: var(--primary); color: #000;">
                    <i class="fab fa-google-play" style="margin-right: 10px;"></i> GET APP
                </a>
                <a href="{{ route('admin.login') }}" class="btn-secondary">ADMIN COMMAND</a>
                <a href="#contact-section" class="btn-secondary" style="scroll-behavior: smooth;">CONTACT US</a>
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

        <!-- NEW: MACHINERY ECOSYSTEM -->
        <section class="features-section" style="padding-top: 5rem;">
            <div class="section-header">
                <h2 class="section-title">Machinery & Tools Ecosystem</h2>
                <p class="hero-description" style="font-size: 1.1rem; margin-top: 1rem;">Complete lifecycle management for your heavy machinery fleet.</p>
            </div>
            <div class="feature-grid-v2">
                <div class="mini-feature glass-panel">
                    <div class="mini-icon">🚜</div>
                    <div class="mini-content">
                        <h4>Machine Catalog</h4>
                        <p>Categorized directory of all heavy equipment and specialized tools.</p>
                    </div>
                </div>
                <div class="mini-feature glass-panel">
                    <div class="mini-icon">⚙️</div>
                    <div class="mini-content">
                        <h4>Fleet Deployment</h4>
                        <p>Track machinery transfers across multiple project sites in real-time.</p>
                    </div>
                </div>
                <div class="mini-feature glass-panel">
                    <div class="mini-icon">🏗️</div>
                    <div class="mini-content">
                        <h4>Site Logistics</h4>
                        <p>Manage working sites and coordinate equipment movement seamlessly.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- NEW: PROCUREMENT & MATERIALS -->
        <section class="features-section" style="background: rgba(0,0,0,0.2); border-radius: 40px; padding: 5rem 2rem;">
            <div class="section-header">
                <h2 class="section-title">Procurement & Site Intelligence</h2>
            </div>
            <div class="feature-grid-v2">
                <div class="mini-feature glass-panel">
                    <div class="mini-icon">📦</div>
                    <div class="mini-content">
                        <h4>Material Registry</h4>
                        <p>Dynamic management of inventory, units, and supply chain logistics.</p>
                    </div>
                </div>
                <div class="mini-feature glass-panel">
                    <div class="mini-icon">💼</div>
                    <div class="mini-content">
                        <h4>HR Forensics</h4>
                        <p>Master human resources, designations, and specialized skill matrices.</p>
                    </div>
                </div>
                <div class="mini-feature glass-panel">
                    <div class="mini-icon">💸</div>
                    <div class="mini-content">
                        <h4>Petty Cash Logic</h4>
                        <p>Automated petty cash management and transparent account coding.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Original Key Features (Refined) -->
        <section class="features-section" id="features">
            <div class="section-header">
                <h2 class="section-title">The Enterprise Advantage</h2>
            </div>

            <div class="features-grid">
                <div class="feature-card glass-panel">
                    <div class="feature-icon-wrapper">🛡️</div>
                    <h3 class="feature-title">High Fidelity Security</h3>
                    <p class="feature-description">
                        Encrypted project data with multi-factor authorization for mission-critical infrastructure.
                    </p>
                </div>

                <div class="feature-card glass-panel">
                    <div class="feature-icon-wrapper">📈</div>
                    <h3 class="feature-title">Operational Scale</h3>
                    <p class="feature-description">
                        Designed to handle thousands of assets and hundreds of sites across global terrains.
                    </p>
                </div>

                <div class="feature-card glass-panel">
                    <div class="feature-icon-wrapper">🤝</div>
                    <h3 class="feature-title">Resource Mesh</h3>
                    <p class="feature-description">
                        Collaborative site logs and material tracking for unified project execution.
                    </p>
                </div>
            </div>
        </section>

        <!-- CONTACT FORM SECTION -->
        <section class="features-section" id="contact-section" style="padding: 8rem 2rem;">
            <div class="section-header">
                <h2 class="section-title">Contact Engineering</h2>
                <p class="hero-description" style="font-size: 1.1rem; margin-top: 1rem;">Initialize a consultation for your next infrastructure masterpiece.</p>
            </div>
            
            <div class="glass-panel" style="max-width: 800px; margin: 0 auto; padding: 4rem; border-radius: 30px;">
                <form action="#" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div style="grid-column: span 1;">
                        <label style="display: block; margin-bottom: 0.8rem; font-weight: 600; color: var(--text-dim); text-transform: uppercase; font-size: 0.8rem;">Full Name</label>
                        <input type="text" placeholder="John Doe" style="width: 100%; padding: 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 12px; color: #fff; outline: none;">
                    </div>
                    <div style="grid-column: span 1;">
                        <label style="display: block; margin-bottom: 0.8rem; font-weight: 600; color: var(--text-dim); text-transform: uppercase; font-size: 0.8rem;">Email Address</label>
                        <input type="email" placeholder="john@enterprise.com" style="width: 100%; padding: 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 12px; color: #fff; outline: none;">
                    </div>
                    <div style="grid-column: span 2;">
                        <label style="display: block; margin-bottom: 0.8rem; font-weight: 600; color: var(--text-dim); text-transform: uppercase; font-size: 0.8rem;">Subject</label>
                        <input type="text" placeholder="Project Inquiry" style="width: 100%; padding: 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 12px; color: #fff; outline: none;">
                    </div>
                    <div style="grid-column: span 2;">
                        <label style="display: block; margin-bottom: 0.8rem; font-weight: 600; color: var(--text-dim); text-transform: uppercase; font-size: 0.8rem;">Technical Message</label>
                        <textarea placeholder="Describe your project requirements..." rows="5" style="width: 100%; padding: 1.2rem; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 12px; color: #fff; outline: none; resize: none;"></textarea>
                    </div>
                    <div style="grid-column: span 2; text-align: center; margin-top: 1rem;">
                        <button type="submit" class="btn-primary" style="width: 100%; background: var(--primary); color: #000; border: none; cursor: pointer;">SUBMIT SPECIFICATIONS</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <p>&copy; {{ date('Y') }} EZ NIRMAN | Ranihati Construction Private Limite | All Rights Reserved.</p>
                <p style="margin-top: 1rem; font-size: 0.8rem; opacity: 0.5;">ISO 9001:2015 CERTIFIED SYSTEM | SECURE ARCHITECTURE</p>
            </div>
        </footer>
    </div>

    <script>
        // Smooth Scroll for Contact Button
        document.querySelector('a[href^="#contact-section"]').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>