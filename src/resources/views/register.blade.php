<x-layout>
    <div class="form-container">
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
                    <small>A jelszónak legalább 5 karakter hosszúnak kell lennie, és tartalmaznia kell legalább 1 számot és 1 nagybetűt!</small>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Jelszó megerősítése:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>
            </div>

            {{-- Magánszemély / Jogi személy (radio) --}}
            <div class="form-row">
                <div class="form-group full-width">
                    <label>Regisztráció típusa:</label>
                    <label><input type="radio" name="account_type" value="personal" required> Magánszemély</label>
                    <label><input type="radio" name="account_type" value="business"> Jogi személy</label>
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
                            <!-- További országok -->
                        </select>
                        <input type="tel" name="phone_number" id="phone" pattern="[0-9]*" inputmode="numeric" placeholder="123456789" required>
                    </div>
                </div>
            </div>

            {{-- Számlázási és szállítási cím --}}
            <div class="form-row address-row">
                <fieldset class="address-fieldset">
                    <legend>Számlázási cím</legend>
                    <select name="billing_country" required>
                        <option value="">Ország</option>
                        <option value="Hungary">Magyarország</option>
                        <option value="USA">USA</option>
                        <option value="UK">UK</option>
                        <option value="Germany">Németország</option>
                        <!-- További országok -->
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

                <fieldset class="address-fieldset">
                    <legend>Szállítási cím</legend>
                    <select name="shipping_country" required>
                        <option value="">Ország</option>
                        <option value="Hungary">Magyarország</option>
                        <option value="USA">USA</option>
                        <option value="UK">UK</option>
                        <option value="Germany">Németország</option>
                        <!-- További országok -->
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
            <div class="form-row checkbox-row vertical">
                <label>
                    <input type="checkbox" name="accept_tos" required>
                    Elfogadom az <a href="#">Általános Szerződési Feltételeket</a>
                </label>
                <label>
                    <input type="checkbox" name="accept_privacy" required>
                    Elfogadom az <a href="#">Adatvédelmi Nyilatkozatot</a>
                </label>
                <label>
                    <input type="checkbox" name="subscribe_newsletter">
                    Feliratkozom a hírlevélre
                </label>
            </div>

            <div class="form-row">
                <button type="submit">Register</button>
            </div>
        </form>

        <div style="text-align:center; margin-top: 1.5em;">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}" class="register-link">Login</a>
        </div>
    </div>

    <style>
.form-container {
    max-width: 900px;
    margin: auto;
    padding: 1em;
    font-family: sans-serif;
}

/* Sorok és rugalmas elrendezés */
.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1em; /* távolság a mezők között */
    margin-bottom: 1em;
}

.form-group {
    flex: 1 1 45%; /* kb. 2 mező fér ki sorban */
    display: flex;
    flex-direction: column;
    min-width: 180px;
}

.full-width {
    flex: 1 1 100%;
    max-width: 600px;   /* <<< ne legyen túl széles */
}

/* Cím mezők */
.address-row {
    display: flex;
    flex-wrap: wrap;
    gap: 2em;
}
.address-fieldset {
    flex: 1 1 48%;
    border: 1px solid #ccc;
    padding: 0.8em;
    border-radius: 8px;
    min-width: 200px;
}

/* Checkbox sor */
.checkbox-row.vertical label {
    display: flex;
    align-items: center;
    gap: 0.5em; /* <<< checkbox közelebb kerül a szöveghez */
    margin-bottom: 0.5em;
}

/* Telefon mező */
.phone-container {
    display: flex;
    gap: 0.5em;
}
.phone-container select {
    flex: 0 0 90px; /* fix szélesség az országkódnak */
}
.phone-container input {
    flex: 1; /* maradék hely */
}

/* Input és select mezők */
input, select, textarea {
    width: 100%;
    padding: 0.5em;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 0.9em;
    box-sizing: border-box;
}

/* De a checkboxok és radio-k ne legyenek szélesek */
input[type="checkbox"],
input[type="radio"] {
    width: auto;
}

/* Gombok */
button {
    padding: 0.65em 1.2em;
    font-size: 0.95em;
    border: none;
    border-radius: 8px;
    background: #007BFF;
    color: #fff;
    cursor: pointer;
    transition: 0.2s;
}
button:hover {
    background: #0056b3;
}

/* Reszponzív */
@media(max-width:768px){
    .form-row { flex-direction: column; }
    .form-group { flex:1 1 100%; }
    .address-row { flex-direction: column; }
    .full-width { max-width: 100%; } /* mobilon nyúlhat teljes szélességre */
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