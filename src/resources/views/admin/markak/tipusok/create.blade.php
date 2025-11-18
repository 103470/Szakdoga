@extends('admin')

@section('content')
<div class="container">
    <h1>Új típus hozzáadása</h1>

    <a href="{{ route('admin.markak.tipusok.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    <form action="{{ route('admin.markak.tipusok.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="brand_id" class="form-label">Márka</label>
            <select name="brand_id" id="brand_id" class="form-control" required>
                <option value="">Válassz márkát...</option>

                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" 
                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Típus neve</label>
            <input type="text" name="name" id="name" class="form-control"
                   required value="{{ old('name') }}">
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
