<x-layout>
    <div class="auth-container">
        <div class="auth-card">

            <h2 class="auth-title">Regisztráció</h2>

            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Név --}}
                <div class="two-columns">
                    <div class="input-group">
                        <label for="lastname">Vezetéknév</label>
                        <input type="text" name="lastname" id="lastname" required autofocus>
                    </div>
                    <div class="input-group">
                        <label for="firstname">Keresztnév</label>
                        <input type="text" name="firstname" id="firstname" required>
                    </div>
                </div>

                {{-- Email --}}
                <div class="two-columns">
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="input-group">
                        <label for="email_confirmation">Email megerősítése</label>
                        <input type="email" name="email_confirmation" id="email_confirmation" required>
                    </div>
                </div>

                {{-- Jelszó --}}
                <div class="two-columns">
                    <div class="input-group">
                        <label for="password">Jelszó</label>
                        <input type="password" name="password" id="password" required>
                        <small>A jelszónak legalább 5 karakter hosszúnak kell lennie, és tartalmaznia kell legalább 1 számot és 1 nagybetűt</small>
                    </div>
                    <div class="input-group">
                        <label for="password_confirmation">Jelszó megerősítése</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>
                </div>

                {{-- Magánszemély / Jogi személy --}}
                <div class="input-group">
                    <label>Regisztráció típusa</label>
                    <div class="radio-group">
                        <label><input type="radio" name="account_type" value="personal" required> Magánszemély</label>
                        <label><input type="radio" name="account_type" value="business"> Jogi személy</label>
                    </div>
                </div>

                {{-- Telefonszám --}}
                <div class="input-group">
                    <label for="phone">Telefonszám</label>
                    <div class="phone-container">
                        <select name="phone_country_code" required>
                            <option value="+36">🇭🇺 +36</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+44">🇬🇧 +44</option>
                            <option value="+49">🇩🇪 +49</option>
                        </select>
                        <input type="tel" name="phone_number" pattern="[0-9]*" inputmode="numeric" placeholder="123456789" required>
                    </div>
                </div>

                {{-- Címek --}}
                <div class="two-columns">
                    <fieldset class="fieldset">
                        <legend>Számlázási cím</legend>
                        <select name="billing_country" required>
                            <option value="">Ország</option>
                            <option value="Hungary">Magyarország</option>
                            <option value="USA">USA</option>
                            <option value="UK">UK</option>
                            <option value="Germany">Németország</option>
                        </select>
                        <input type="text" name="billing_zip" placeholder="Irányítószám" pattern="\d*" inputmode="numeric" required>
                        <input type="text" name="billing_city" placeholder="Város" required>
                        <input type="text" name="billing_street_name" placeholder="Közterület neve" required>
                        <input type="text" name="billing_street_type" placeholder="Közterület jellege" required>
                        <input type="text" name="billing_house_number" placeholder="Házszám" pattern="\d*" inputmode="numeric" required>
                        <input type="text" name="billing_building" placeholder="Épület">
                        <input type="text" name="billing_floor" placeholder="Emelet">
                        <input type="text" name="billing_door" placeholder="Ajtó">
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend>Szállítási cím</legend>
                        <select name="shipping_country" required>
                            <option value="">Ország</option>
                            <option value="Hungary">Magyarország</option>
                            <option value="USA">USA</option>
                            <option value="UK">UK</option>
                            <option value="Germany">Németország</option>
                        </select>
                        <input type="text" name="shipping_zip" placeholder="Irányítószám" pattern="\d*" inputmode="numeric" required>
                        <input type="text" name="shipping_city" placeholder="Város" required>
                        <input type="text" name="shipping_street_name" placeholder="Közterület neve" required>
                        <input type="text" name="shipping_street_type" placeholder="Közterület jellege" required>
                        <input type="text" name="shipping_house_number" placeholder="Házszám" pattern="\d*" inputmode="numeric" required>
                        <input type="text" name="shipping_building" placeholder="Épület">
                        <input type="text" name="shipping_floor" placeholder="Emelet">
                        <input type="text" name="shipping_door" placeholder="Ajtó">
                    </fieldset>
                </div>

                {{-- Elfogadások --}}
                <div class="checkbox-group">
                    <label><input type="checkbox" name="accept_tos" required> Elfogadom az ÁSZF-et</label>
                    <label><input type="checkbox" name="accept_privacy" required> Elfogadom az Adatvédelmi Nyilatkozatot</label>
                    <label><input type="checkbox" name="subscribe_newsletter"> Feliratkozom a hírlevélre</label>
                </div>

                <button type="submit" class="auth-btn">Regisztráció</button>
            </form>

            <div class="auth-footer">
                Már van fiókod?
                <a href="{{ route('login') }}">Jelentkezz be</a>
            </div>

        </div>
    </div>

    <style>
        .auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f7f8fa;
    padding: 1rem;
    box-sizing: border-box;
}

/* Card */
.auth-card {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    max-width: 1000px;   /* nagy képernyőn a kártya szélessége */
    width: 100%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    box-sizing: border-box;
}

/* Cím */
.auth-title {
    text-align: center;
    margin-bottom: 1.25rem;
    font-size: 1.55rem;
    font-weight: 600;
}

/* Rács - két oszlopos részek */
.two-columns {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    box-sizing: border-box;
}

/* Egy mezőblokk: rugalmas, de max szélességet is tartunk */
.input-group {
    flex: 1 1 48%;       /* alap: két oszlop */
    min-width: 220px;    /* ha kisebb a hely, törik alá */
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
}

/* Label + input */
label {
    font-weight: 600;
    margin-bottom: 0.35rem;
}
input[type="text"],
input[type="email"],
input[type="password"],
input[type="tel"],
select {
    padding: 0.65rem;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    font-size: 0.95rem;
    width: 100%;
    box-sizing: border-box;
}

/* Fieldset-ek: két oszloposként viselkednek, de nem törik egymásra */
.fieldset {
    border: 1px solid #e5e7eb;
    padding: 0.9rem;
    border-radius: 8px;
    flex: 1 1 48%;
    min-width: 260px;
    box-sizing: border-box;
}

/* Telefon mező: fix ország kód + rugalmas szám */
.phone-container {
    display: flex;
    gap: 0.5rem;
}
.phone-container select {
    flex: 0 0 110px;
    max-width: 140px;
    box-sizing: border-box;
}
.phone-container input {
    flex: 1 1 auto;
    box-sizing: border-box;
}

/* Radio és checkbox csoportok */
.radio-group, .checkbox-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 0.4rem;
}
.checkbox-group { flex-direction: column; }

/* Gomb */
.auth-btn {
    width: 100%;
    padding: 0.85rem;
    background: #007bff;
    border: none;
    color: white;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
}
.auth-btn:hover { background: #0056b3; }

/* Footer link */
.auth-footer {
    text-align: center;
    margin-top: 1rem;
}

/* Apró segédek (kis leírások) */
small {
    color: #6b7280;
    margin-top: 0.35rem;
    display: block;
}

/* Mobil: egy oszlopos elrendezés, biztos nem csúszik össze */
@media (max-width: 880px) {
    .input-group, .fieldset {
        flex: 1 1 100%;
        min-width: 0;
    }
    .phone-container select {
        flex: 0 0 95px;
    }
}

/* Nagyon kicsi képernyő esetén (pl. 360px) egy kis padding csökkentés */
@media (max-width: 420px) {
    .auth-card {
        padding: 1rem;
    }
    .auth-title { font-size: 1.3rem; }
}
    </style>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            let form = e.target;
            let email = form.email.value;
            let emailConfirm = form.email_confirmation.value;
            let password = form.password.value;
            let passwordConfirm = form.password_confirmation.value;
            let passwordRegex = /^(?=.*[A-Z])(?=.*\d).{5,}$/;

            if(email !== emailConfirm) {
                alert('Az email címek nem egyeznek.');
                e.preventDefault();
                return;
            }

            if(!passwordRegex.test(password)) {
                alert('A jelszó nem felel meg a szabályoknak.');
                e.preventDefault();
                return;
            }

            if(password !== passwordConfirm) {
                alert('A jelszó megerősítése nem egyezik.');
                e.preventDefault();
                return;
            }

            if(!form.accept_tos.checked || !form.accept_privacy.checked) {
                alert('El kell fogadni a feltételeket és az adatvédelmi nyilatkozatot.');
                e.preventDefault();
                return;
            }
        });
    </script>
</x-layout>