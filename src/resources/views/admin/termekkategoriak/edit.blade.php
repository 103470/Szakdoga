@extends('admin')

@section('content')
<div class="container">
    <h1>Termékkategória szerkesztése – {{ $productcategory->name }}</h1>

    <a href="{{ route('admin.termekkategoriak.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.termekkategoriak.update', $productcategory->slug) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Termékkategória neve</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $productcategory->name) }}">
        </div>

        <div class="mb-3">
            <label for="subcategory_id" class="form-label">Alkategória</label>
            <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                <option value="">-- Válassz alkategóriát --</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->subcategory_id }}" 
                        {{ (old('subcategory_id', $productcategory->subcategory_id) == $subcategory->subcategory_id) ? 'selected' : '' }}>
                        {{ $subcategory->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Mentés</button>
    </form>
</div>
@endsection
