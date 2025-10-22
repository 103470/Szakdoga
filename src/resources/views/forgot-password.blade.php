<x-layout>
    <div class="form-container">
        <h2>Elfelejtett jelszó</h2>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div>
                <label for="email">Email cím:</label>
                <input type="email" name="email" id="email" required autofocus>
            </div>
            <button type="submit">Jelszó visszaállító link küldése</button>
        </form>

        @if (session('status'))
            <p style="color: green; margin-top: 1em;">{{ session('status') }}</p>
        @endif

        @error('email')
            <p style="color: red; margin-top: 1em;">{{ $message }}</p>
        @enderror
    </div>
</x-layout>
