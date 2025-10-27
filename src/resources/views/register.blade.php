<x-layout>
    <div class="form-container">
        <h2 class="form-title">Regisztráció</h2>

        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Név --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="lastname">Vezetéknév:</label>
                    <input type="text" name="lastname" id="lastname" required autofocus>
                </div>
                <div class="form-group">
                    <label for="firstname">Keresztnév:</label>
                    <input type="text" name="firstname" id="firstname" required>
                </div>
            </div>

            {{-- Email --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="email_confirmation">Email megerősítése:</label>
                    <input type="email" name="email_confirmation" id="email_confirmation" required>
                </div>
            </div>

            {{-- Jelszó --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Jelszó:</label>
                    <input type="password" name="password" id="password" required>
                    <small>A jelszónak legalább 5 karakter hosszúnak kell lennie, és tartalmaznia kell legalább 1 számot és 1 nagybetűt.</small>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Jelszó megerősítése:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>
            </div>

            {{-- Regisztráció típusa --}}
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Regisztráció típusa:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="account_type" value="personal" required> Magánszemély</label>
                        <label><input type="radio" name="account_type" value="business"> Jogi személy</label>
                    </div>
                </div>
            </div>

            {{-- Telefonszám --}}
            <div class="form-row">
                <div class="form-group full-width">
                    <label for="phone">Telefonszám:</label>
                    <div class="phone-container">
                        <select name="phone_country_code" required>
                            <option value="+36">🇭🇺 +36</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+44">🇬🇧 +44</option>
                            <option value="+49">🇩🇪 +49</option>
                        </select>
                        <input type="tel" name="phone_number" id="phone" pattern="[0-9]*" inputmode="numeric" placeholder="123456789" required>
                    </div>
                </div>
            </div>

            {{-- Címek --}}
            <div class="form-row address-row">
                <fieldset class="address-fieldset">
                    <legend>Számlázási cím</legend>
                    <div class="address-fields">
                        <select name="billing_country" required>
                            <option value="">Ország</option>
                            <option value="Hungary">Magyarország</option>
                            <option value="USA">USA</option>
                            <option value="UK">UK</option>
                            <option value="Germany">Németország</option>
                        </select>
                        <input type="text" name="billing_zip" placeholder="Irányítószám" pattern="\d*" required>
                        <input type="text" name="billing_city" placeholder="Város" required>
                        <input type="text" name="billing_street_name" placeholder="Közterület neve" required>
                        <input type="text" name="billing_street_type" placeholder="Közterület jellege" required>
                        <input type="text" name="billing_house_number" placeholder="Házszám" pattern="\d*" required>
                        <input type="text" name="billing_building" placeholder="Épület">
                        <input type="text" name="billing_floor" placeholder="Emelet">
                        <input type="text" name="billing_door" placeholder="Ajtó">
                    </div>
                </fieldset>

                <fieldset class="address-fieldset">
                    <legend>Szállítási cím</legend>
                    <div class="address-fields">
                        <select name="shipping_country" required>
                            <option value="">Ország</option>
                            <option value="Hungary">Magyarország</option>
                            <option value="USA">USA</option>
                            <option value="UK">UK</option>
                            <option value="Germany">Németország</option>
                        </select>
                        <input type="text" name="shipping_zip" placeholder="Irányítószám" pattern="\d*" required>
                        <input type="text" name="shipping_city" placeholder="Város" required>
                        <input type="text" name="shipping_street_name" placeholder="Közterület neve" required>
                        <input type="text" name="shipping_street_type" placeholder="Közterület jellege" required>
                        <input type="text" name="shipping_house_number" placeholder="Házszám" pattern="\d*" required>
                        <input type="text" name="shipping_building" placeholder="Épület">
                        <input type="text" name="shipping_floor" placeholder="Emelet">
                        <input type="text" name="shipping_door" placeholder="Ajtó">
                    </div>
                </fieldset>
            </div>

            {{-- Elfogadások --}}
            <div class="form-row checkbox-row vertical">
                <label><input type="checkbox" name="accept_tos" required> Elfogadom az <a href="#">ÁSZF-et</a></label>
                <label><input type="checkbox" name="accept_privacy" required> Elfogadom az <a href="#">Adatvédelmi Nyilatkozatot</a></label>
                <label><input type="checkbox" name="subscribe_newsletter"> Feliratkozom a hírlevélre</label>
            </div>

            <div class="form-row">
                <button type="submit">Regisztráció</button>
            </div>
        </form>

        <div class="login-link">
            Már van fiókod? <a href="{{ route('login') }}">Jelentkezz be</a>
        </div>
    </div>

    <style>
        body {
            background: linear-gradient(145deg, #d8d8d8 0%, #f4f4f4 100%);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .form-container {
            background: #ffffff;
            padding: 3em 2.5em;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            max-width: 950px;
            margin: 3em auto;
        }

        .form-title {
            text-align: center;
            font-size: 1.8em;
            font-weight: 600;
            color: #2c2c2c;
            margin-bottom: 1.8em;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5em;
            margin-bottom: 1.5em;
        }

        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }

        .full-width { flex: 1 1 100%; }

        label {
            font-weight: 500;
            color: #2c2c2c;
            margin-bottom: 0.4em;
        }

        input, select {
            padding: 0.9em;
            border: 1px solid #c5c5c5;
            border-radius: 8px;
            background: #fafafa;
            font-size: 0.95em;
            transition: all 0.2s;
        }

        input:focus, select:focus {
            border-color: #555;
            box-shadow: 0 0 6px rgba(85, 85, 85, 0.3);
            outline: none;
        }

        small {
            font-size: 0.8em;
            color: #666;
            margin-top: 0.3em;
        }

        .phone-container {
            display: flex;
            gap: 0.5em;
        }

        fieldset {
            border: 1px solid #d0d0d0;
            border-radius: 10px;
            padding: 1.5em;
            background: #f9f9f9;
            flex: 1 1 45%;
        }

        legend {
            padding: 0 0.8em;
            font-weight: 600;
            color: #333;
        }

        .address-fields {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1em;
        }

        .checkbox-row.vertical label {
            display: flex;
            align-items: center;
            gap: 0.5em;
            margin-bottom: 0.6em;
            font-size: 0.95em;
        }

        button {
            width: 100%;
            padding: 1em;
            border: none;
            border-radius: 8px;
            font-size: 1.05em;
            font-weight: 600;
            background: linear-gradient(90deg, #3a3a3a, #1f1f1f);
            color: white;
            cursor: pointer;
            transition: background 0.3s, transform 0.15s;
        }

        button:hover {
            background: linear-gradient(90deg, #1f1f1f, #3a3a3a);
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 1.8em;
            font-size: 0.95em;
            color: #333;
        }

        .login-link a {
            color: #000;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        @media(max-width: 768px) {
            .form-row { flex-direction: column; }
            fieldset { flex: 1 1 100%; }
        }
    </style>

    {{-- VALIDÁLÁS --}}
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const f = e.target;
            const pw = f.password.value;
            const pw2 = f.password_confirmation.value;
            const em = f.email.value;
            const em2 = f.email_confirmation.value;
            const pwRegex = /^(?=.*[A-Z])(?=.*\d).{5,}$/;

            if (em !== em2) {
                alert('Az email címek nem egyeznek.');
                e.preventDefault(); return;
            }
            if (!pwRegex.test(pw)) {
                alert('A jelszó nem felel meg a követelményeknek.');
                e.preventDefault(); return;
            }
            if (pw !== pw2) {
                alert('A jelszó megerősítése nem egyezik.');
                e.preventDefault(); return;
            }
            if (!f.accept_tos.checked || !f.accept_privacy.checked) {
                alert('El kell fogadni az ÁSZF-et és az Adatvédelmi Nyilatkozatot.');
                e.preventDefault(); return;
            }
        });
    </script>
</x-layout>
