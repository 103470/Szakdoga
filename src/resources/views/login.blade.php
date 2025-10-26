<x-layout>
    <div class="login-page">
        <div class="login-container">
            <!-- BAL OLDAL: BEJELENTKEZÉS -->
            <div class="login-left">
                <div class="login-box">
                    <img src="/images/logo.png" alt="Logo" class="login-logo">

                    <h1 class="login-title">Jelentkezz be</h1>
                    <p class="login-subtitle">Lépj be fiókodba, hogy megtekinthesd rendeléseidet és autóalkatrészeidet.</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email cím</label>
                            <input type="email" name="email" id="email" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password">Jelszó</label>
                            <input type="password" name="password" id="password" required>
                        </div>

                        <div class="form-footer">
                            <label><input type="checkbox" name="remember"> Emlékezz rám</label>
                            <p class="forgot-password"><a href="{{ route('password.request') }}">
                                Elfelejtetted a jelszavad?</a>
                            </p>
                        </div>

                        <button type="submit" class="btn-primary">Bejelentkezés</button>
                    </form>

                    <div class="divider">vagy</div>

                    <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn-google">Bejelentkezés Google-lal</a>

                    <p class="register-link">
                        Nincs még fiókod? <a href="{{ route('register') }}">Regisztrálj most</a>
                    </p>
                </div>
            </div>

            <!-- JOBB OLDAL: KÉP ÉS SZÖVEG -->
            <div class="login-right">
                <div class="promo-text">
                    <h2>EREDETI / UTÁNGYÁRTOTT / TELJESÍTMÉNY ALKATRÉSZEK</h2>
                    <h1>AUTÓALKATRÉSZEK EURÓPAI MÁRKÁKHOZ</h1>
                    <p>
                        Online katalógusunk több mint 190.000 prémium minőségű alkatrészt tartalmaz,
                        beleértve a BMW, Audi, Volkswagen, Mercedes és Porsche modelleket.
                    </p>
                </div>
                <img src="/images/auto-parts-banner.jpg" alt="Autóalkatrészek" class="promo-img">
            </div>
        </div>
    </div>

    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f3f3f3; }

        .login-container {
            display: flex;
            height: 100vh;
        }

        .login-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2em;
            background: #f9f9f9;
        }

        .login-right {
            flex: 1.2;
            position: relative;
            color: white;
            overflow: hidden;
        }

        .promo-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(45%);
        }

        .promo-text {
            position: relative;
            z-index: 2;
            padding: 3em;
            max-width: 600px;
        }

        .promo-text h2 {
            font-size: 0.9em;
            letter-spacing: 2px;
            color: #333;
            text-transform: uppercase;
        }

        .promo-text h1 {
            font-size: 2em;
            margin: 0.5em 0;
            color: #333;
            font-weight: 700;
        }

        .promo-text p {
            line-height: 1.6;
            color: #333;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 2em;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .login-logo {
            width: 120px;
            margin-bottom: 1em;
        }

        .login-title {
            font-size: 1.6em;
            font-weight: 700;
            margin-bottom: 0.2em;
        }

        .login-subtitle {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 1.5em;
        }

        .form-group {
            margin-bottom: 1em;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.3em;
            font-weight: 600;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.7em;
            border: 1px solid #333;
            border-radius: 8px;
            font-size: 1em;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85em;
            margin-bottom: 1.5em;
        }

        .forgot-password a {
            color: #555;
            text-decoration: none;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 0.8em;
            background: #f3f3f3;
            color: #2c2c2c;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background: #1a1a1a;
        }

        .btn-google {
            display: block;
            width: 100%;
            text-align: center;
            background: #ea4335;
            color: white;
            padding: 0.8em;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 0.5em;
        }

        .btn-google:hover {
            background: #c33d2e;
        }

        .divider {
            text-align: center;
            color: #888;
            margin: 1em 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #333;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .register-link {
            margin-top: 1.5em;
            text-align: center;
            font-size: 0.9em;
        }

        .register-link a {
            color: #555;
            font-weight: 600;
            text-decoration: none;
        }

        @media (max-width: 900px) {
            .login-container { flex-direction: column; }
            .login-right { display: none; }
            .login-left { background: white; }
        }
    </style>
</x-layout>
