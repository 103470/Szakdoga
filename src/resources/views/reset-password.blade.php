<x-layout>
    <div class="auth-container">
        <div class="auth-card">
            <h2 class="auth-title">Új jelszó megadása</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-group">
                    <label for="email">Email cím</label>
                    <input type="email" name="email" id="email" required autofocus>
                </div>

                <div class="input-group">
                    <label for="password">Új jelszó</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Új jelszó megerősítése</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                </div>

                <button type="submit" class="auth-btn">Jelszó mentése</button>
            </form>
        </div>
    </div>
</x-layout>
