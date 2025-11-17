<x-layout>
    <div class="reg-box">
        <h2 class="reg-title">Regisztráció</h2>

        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="input-group">
                <label>Vezetéknév*</label>
                <input type="text" name="lastname" required autofocus>
            </div>

            <div class="input-group">
                <label>Keresztnév*</label>
                <input type="text" name="firstname" required>
            </div>

            <div class="input-group">
                <label>Email*</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label>Email megerősítése*</label>
                <input type="email" name="email_confirmation" required>
            </div>

            <div class="input-group">
                <label>Jelszó*</label>
                <input type="password" name="password" required>
                <small>Min. 5 karakter, 1 szám, 1 nagybetű</small>
            </div>

            <div class="input-group">
                <label>Jelszó megerősítése*</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <div class="input-group">
                <label>Telefonszám*</label>
                <div class="phone-line">
                    <select name="phone_country_code" required>
                        <option value="+36">🇭🇺 +36</option>
                        <option value="+1">🇺🇸 +1</option>
                        <option value="+44">🇬🇧 +44</option>
                        <option value="+49">🇩🇪 +49</option>
                    </select>
                    <input type="tel" name="phone_number" placeholder="123456789" required>
                </div>
            </div>

            <div class="input-group">
                <label>Regisztráció típusa*</label>
                <div class="radio-col">
                    <label><input type="radio" name="account_type" value="personal" required> Magánszemély</label>
                    <label><input type="radio" name="account_type" value="business"> Jogi személy</label>
                </div>
            </div>

            <!-- SZÁMLÁZÁSI CÍM -->
            <h4 class="section-title">Számlázási cím</h4>

            <div class="grid">
                <select name="billing_country" required>
                    <option value="">Ország</option>
                    <option>Magyarország</option>
                </select>
                <input type="text" name="billing_zip" placeholder="Irányítószám" required>
                <input type="text" name="billing_city" placeholder="Város" required>
                <input type="text" name="billing_street_name" placeholder="Közterület neve" required>
                <input type="text" name="billing_street_type" placeholder="Közterület jellege" required>
                <input type="text" name="billing_house_number" placeholder="Házszám" required>
                <input type="text" name="billing_building" placeholder="Épület">
                <input type="text" name="billing_floor" placeholder="Emelet">
                <input type="text" name="billing_door" placeholder="Ajtó">
            </div>

            <!-- SZÁLLÍTÁSI CÍM -->
            <h4 class="section-title">Szállítási cím</h4>

            <div class="grid">
                <select name="shipping_country" required>
                    <option value="">Ország</option>
                    <option>Magyarország</option>
                </select>
                <input type="text" name="shipping_zip" placeholder="Irányítószám" required>
                <input type="text" name="shipping_city" placeholder="Város" required>
                <input type="text" name="shipping_street_name" placeholder="Közterület neve" required>
                <input type="text" name="shipping_street_type" placeholder="Közterület jellege" required>
                <input type="text" name="shipping_house_number" placeholder="Házszám" required>
                <input type="text" name="shipping_building" placeholder="Épület">
                <input type="text" name="shipping_floor" placeholder="Emelet">
                <input type="text" name="shipping_door" placeholder="Ajtó">
            </div>

            <div class="checks">
                <label>
                    <input type="checkbox" name="accept_tos" required>
                    <a href="/aszf" target="_blank">ÁSZF elfogadása</a>
                </label>

                <label>
                    <input type="checkbox" name="accept_privacy" required>
                    <a href="/adatvedelem" target="_blank">Adatvédelem elfogadása</a>
                </label>

                <label>
                    <input type="checkbox" name="subscribe_newsletter">
                    <span>Hírlevél feliratkozás</span>
                </label>
            </div>

            <button type="submit" class="btn-main">Regisztráció</button>

            <p class="login">Már van fiókod? <a href="{{ route('login') }}">Bejelentkezés</a></p>
        </form>
    </div>


<style>
body {
    background: #f1f1f1;
    font-family: 'Segoe UI';
}
.reg-box {
    max-width: 380px;
    margin: 40px auto;
    padding: 32px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 6px 30px rgba(0,0,0,.07);
}
.reg-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 25px;
    font-weight: 600;
}
.input-group {
    margin-bottom: 18px;
}
label {
    font-size: 14px;
    font-weight: 600;
}
input, select {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border-radius: 8px;
    border: 1px solid #c8c8c8;
    background: #fafafa;
}
input:focus {
    border-color: #444;
    outline: none;
}
.phone-line {
    display: flex;
    gap: 8px;
}
.radio-col input[type="radio"] {
    appearance: none;
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #000000ff; /* témád színe */
    border-radius: 50%;
    cursor: pointer;
    position: relative;
    transition: 0.2s;
}

.radio-col input[type="radio"]:checked {
    border-color: #2c2c2c;
    background-color: #2c2c2c;
}


.section-title {
    margin: 22px 0 10px;
    font-size: 15px;
    font-weight: 700;
}
.grid {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 10px;
    margin-bottom: 10px;
}
.checks {
    margin: 18px 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
    
}

.checks a {
    color: #222;
    text-decoration: none;
    font-weight: 200;
}

.checks label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.3;
}

.checks input[type="checkbox"] {
    appearance: none;
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #000000ff;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    transition: 0.2s;
}

.checks input[type="checkbox"]:checked {
    background-color: #2c2c2c;
    border-color: #2c2c2c;
}

.checks label:hover {
    color: #000;
}

.btn-main {
    width: 100%;
    padding: 12px;
    background: linear-gradient(90deg,#3a3a3a,#1a1a1a);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
}
.btn-main:hover {
    background: linear-gradient(90deg,#1a1a1a,#3a3a3a);
}
.login {
    text-align: center;
    margin-top: 18px;
    font-size: 14px;
}
.login a { font-weight: bold; color: black; }


</style>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const f = e.target;
    if (f.email.value !== f.email_confirmation.value) {
        alert('Az email címek nem egyeznek.'); e.preventDefault();
    }
    if (f.password.value !== f.password_confirmation.value) {
        alert('A jelszó nem egyezik.'); e.preventDefault();
    }
});
</script>
</x-layout>
