<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Command Login - EZ NIRMAN</title>
    <link rel="icon" href="{{ asset('assets/images/logo.gif') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/frontend/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        .login-card {
            width: 100%;
            max-width: 480px;
            padding: 4rem 3rem;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .login-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            margin: 1.5rem 0 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }
        .form-label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 600;
            color: var(--text-dim);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .form-input {
            width: 100%;
            padding: 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: #fff;
            outline: none;
            transition: all 0.3s;
            font-size: 1rem;
        }
        .form-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
            box-shadow: 0 0 20px rgba(255, 184, 0, 0.2);
        }
        .password-toggle {
            position: absolute;
            right: 1.2rem;
            bottom: 1.1rem;
            color: var(--text-dim);
            cursor: pointer;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: var(--primary);
        }
        .btn-submit {
            width: 100%;
            padding: 1.4rem;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 50px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 30px var(--primary-glow);
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1rem;
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px var(--primary-glow);
        }
        #alert-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .alert {
            padding: 1.2rem 2rem;
            border-radius: 12px;
            color: #fff;
            margin-bottom: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .alert-error { background: rgba(255, 71, 87, 0.8); }
        .alert-success { background: rgba(46, 213, 115, 0.8); }
    </style>
</head>
<body class="login-page">
    <div class="cinematic-bg"></div>
    <div class="cinematic-overlay"></div>

    <div class="login-card glass-panel">
        <div class="login-header">
            <img src="{{ asset('assets/images/logo.gif') }}" width="70" alt="EZ NIRMAN">
            <h1 class="login-title">COMMAND LOGIN</h1>
            <p style="color: var(--text-dim); font-weight: 600;">Secure Portal Authorization</p>
        </div>

        <form action="{{ route('admin.login.verify') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Architect/Admin ID</label>
                <input type="email" name="email" class="form-input" placeholder="admin@eznirman.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Authorization Key</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
                <span class="password-toggle" onclick="togglePassword()">
                    <i class="fa-solid fa-eye" id="toggleIcon"></i>
                </span>
            </div>
            <button type="submit" class="btn-submit">INITIALIZE ACCESS</button>
        </form>

        <div style="margin-top: 3rem; text-align: center;">
            <a href="{{ route('home') }}" style="color: var(--text-dim); text-decoration: none; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">← Secure Exit</a>
        </div>
    </div>

    @if (session('success') || session('error') || $errors->any())
    <div id="alert-container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif
    </div>
    @endif

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 400);
                }, 4000);
            });
        });
    </script>
</body>
</html>