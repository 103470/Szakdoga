@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Üzemanyag típus szerkesztése</h1>

    <a href="{{ route('admin.uzemanyag.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.uzemanyag.update', $fuel->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Név</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $fuel->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="is_universal" class="form-label">Univerzális?</label>
            <select name="is_universal" id="is_universal" class="form-select" required>
                <option value="1" {{ old('is_universal', $fuel->is_universal) == 1 ? 'selected' : '' }}>Univerzális</option>
                <option value="0" {{ old('is_universal', $fuel->is_universal) == 0 ? 'selected' : '' }}>Nem univerzális</option>
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
