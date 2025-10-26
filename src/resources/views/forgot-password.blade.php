<x-layout>
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">Jelszó visszaállítása</h2>

            @if (session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="input-group">
                    <label for="email">Email cím</label>
                    <input type="email" name="email" id="email" required autofocus>
                </div>

                <button type="submit" class="auth-btn">Jelszó-visszaállító email küldése</button>
            </form>

            <div class="auth-footer">
                <a href="{{ route('login') }}">Vissza a bejelentkezéshez</a>
            </div>
        </div>
    </div>
</x-layout>
