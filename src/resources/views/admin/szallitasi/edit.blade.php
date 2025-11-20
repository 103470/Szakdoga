@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Szállítási mód szerkesztése</h1>

    <a href="{{ route('admin.szallitasi.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.szallitasi.update', $option->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Név</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $option->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ár (Ft)</label>
            <input type="number" name="price" class="form-control"
                   value="{{ old('price', $option->price) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Leírás</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $option->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="is_active" class="form-label">Állapot</label>
            <select name="is_active" id="is_active" class="form-select">
                <option value="1" @selected(old('is_active', $option->is_active ?? 1) == 1)>Aktív</option>
                <option value="0" @selected(old('is_active', $option->is_active ?? 1) == 0)>Inaktív</option>
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
