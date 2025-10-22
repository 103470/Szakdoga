<x-layout>
    <div class="form-container">
        <h2>Új jelszó megadása</h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <label for="password">Új jelszó:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div>
                <label for="password_confirmation">Jelszó megerősítése:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <button type="submit">Jelszó módosítása</button>
        </form>

        @error('email')
            <p style="color: red;">{{ $message }}</p>
        @enderror
    </div>
</x-layout>
