<x-layout>
    <div class="login-container">
        <div class="login-card">
            <h2 class="login-title">Bejelentkezés</h2>

            {{-- SUCCESS / ERROR ÜZENETEK --}}
            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email cím</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                </div>

                {{-- Password + Forgot --}}
                <div class="form-group password-group">
                    <label for="password">Jelszó</label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Elfelejtett jelszó?</a>
                    <input type="password" name="password" id="password" required>
                </div>

                {{-- Remember Me --}}
                <div class="form-group remember-me">
                    <label>
                        <input type="checkbox" name="remember">
                        Emlékezz rám
                    </label>
                </div>

                {{-- Login Button --}}
                <button type="submit" class="btn-login">Bejelentkezés</button>
            </form>

            {{-- Social Login --}}
            <div class="divider">
                <span>vagy jelentkezz be</span>
            </div>

            <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="social-btn google">
                Google fiókkal
            </a>

            {{-- Register link --}}
            <div class="register-link">
                Nincs még fiókod?
                <a href="{{ route('register') }}">Regisztráció</a>
            </div>
        </div>
    </div>

    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
            background: #f4f6f9;
        }
        .login-card {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-title {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        .alert {
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }
        .form-group {
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        input[type="email"], input[type="password"] {
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .password-group {
            position: relative;
        }
        .forgot-link {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 0.85rem;
            text-decoration: none;
            color: #007BFF;
        }
        .forgot-link:hover { text-decoration: underline; }

        .remember-me {
            display: flex;
            align-items: center;
        }
        .remember-me input {
            margin-right: 0.5rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s;
        }
        .btn-login:hover {
            background: #0056b3;
        }

        .divider {
            text-align: center;
            margin: 1.2rem 0;
            position: relative;
        }
        .divider span {
            background: #fff;
            padding: 0 0.5rem;
            position: relative;
            z-index: 1;
        }
        .divider:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            border-top: 1px solid #ccc;
            z-index: 0;
        }

        .social-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            color: #fff;
            margin-bottom: 0.5rem;
            transition: background 0.2s;
        }
        .social-btn.google {
            background: #ea4335;
        }
        .social-btn.google:hover {
            background: #c33d2e;
        }

        .register-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .register-link a {
            color: #007BFF;
            font-weight: 600;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</x-layout>