@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Fizetési opció szerkesztése</h1>

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

    <form action="{{ route('admin.fizetesi.update', $option->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Név</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $option->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fee</label>
            <input type="text" name="fee" class="form-control" value="{{ old('fee', $option->fee) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Leírás</label>
            <textarea name="description" class="form-control">{{ old('description', $option->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Típus</label>
            <select name="type" class="form-select" required>
                <option value="card" {{ old('type', $option->type)=='card' ? 'selected' : '' }}>CARD</option>
                <option value="cod" {{ old('type', $option->type)=='cod' ? 'selected' : '' }}>COD</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Állapot</label>
            <select name="is_active" class="form-select" required>
                <option value="1" {{ old('is_active', $option->is_active)==1 ? 'selected' : '' }}>Aktív</option>
                <option value="0" {{ old('is_active', $option->is_active)==0 ? 'selected' : '' }}>Inaktív</option>
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
