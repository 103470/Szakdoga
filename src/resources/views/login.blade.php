<x-layout>
    <div class="form-container">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required autofocus>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="social-login">
            <div style="text-align:center; margin-bottom: 1em;">Or login with</div>
            <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="social-btn google">Google</a>
        </div>
        <div style="text-align:center; margin-top: 1.5em;">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}" class="register-link">Register</a>
        </div>
    </div>
    <style>
        .social-login {
            display: flex;
            flex-direction: column;
            gap: 0.5em;
            margin-top: 1.5em;
        }
        .social-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 0.75em;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            color: #fff;
            margin-bottom: 0.25em;
            transition: background 0.2s;
        }
        .social-btn.google {
            background: #ea4335;
        }
        .social-btn.google:hover {
            background: #c33d2e;
        }
        .social-btn.facebook {
            background: #1877f2;
        }
        .social-btn.facebook:hover {
            background: #145dbf;
        }
        .social-btn.apple {
            background: #222;
        }
        .social-btn.apple:hover {
            background: #444;
        }
        .social-btn.github {
            background: #333;
        }
        .social-btn.github:hover {
            background: #555;
        }
    </style>
</x-layout>
