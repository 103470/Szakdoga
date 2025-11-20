@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Új fizetési opció létrehozása</h1>

    <a href="{{ route('admin.fizetesi.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.fizetesi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Név</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fee</label>
            <input type="text" name="fee" class="form-control" value="{{ old('fee') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Leírás</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Típus</label>
            <select name="type" class="form-select" required>
                <option value="">-- Válassz típust --</option>
                <option value="card" {{ old('type')=='card' ? 'selected' : '' }}>CARD</option>
                <option value="cod" {{ old('type')=='cod' ? 'selected' : '' }}>COD</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Állapot</label>
            <select name="is_active" class="form-select" required>
                <option value="1" {{ old('is_active')==1 ? 'selected' : '' }}>Aktív</option>
                <option value="0" {{ old('is_active')==0 ? 'selected' : '' }}>Inaktív</option>
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
