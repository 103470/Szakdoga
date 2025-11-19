@extends('admin')

@section('content')
<div class="container">
    <h1>Új termékkategória hozzáadása</h1>

    <a href="{{ route('admin.termekkategoriak.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.termekkategoriak.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Termékkategória neve</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Pl.: Motorolaj" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="subcategory_id" class="form-label">Alkategória</label>
            <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                <option value="">-- Válassz alkategóriát --</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->subcategory_id }}" {{ old('subcategory_id') == $subcategory->subcategory_id ? 'selected' : '' }}>
                        {{ $subcategory->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Mentés</button>
    </form>
</div>
@endsection
