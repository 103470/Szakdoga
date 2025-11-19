@extends('admin')

@section('content')
<div class="container">
    <h1>Új alkategória hozzáadása</h1>

    <a href="{{ route('admin.alkategoriak.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.alkategoriak.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategória</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Válassz kategóriát...</option>
                @foreach($categories as $category)
                    <option value="{{ $category->kategory_id }}" {{ old('category_id') == $category->kategory_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Alkategória neve</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Pl.: Motorolaj" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="fuel_type_id" class="form-label">Fuel Type</label>
            <select name="fuel_type_id" id="fuel_type_id" class="form-control">
                <option value="">Válassz fuel type-ot...</option>
                @foreach($fuelTypes as $fuel)
                    <option value="{{ $fuel->id }}" {{ old('fuel_type_id') == $fuel->id ? 'selected' : '' }}>
                        {{ $fuel->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Mentés</button>
    </form>
</div>
@endsection
