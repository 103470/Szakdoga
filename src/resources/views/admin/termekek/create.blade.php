@extends('admin')

@section('content')
<div class="container">
    <h1>Új termék hozzáadása</h1>

    <a href="{{ route('admin.termekek.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.termekek.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="subcategory_id" class="form-label">Alkategória</label>
            <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                <option value="">Válassz alkategóriát</option>
                @foreach($subcategories as $sub)
                    <option value="{{ $sub->subcategory_id }}" {{ old('subcategory_id') == $sub->subcategory_id ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="product_category_id" class="form-label">Termékkategória</label>
            <select name="product_category_id" id="product_category_id" class="form-select">
                <option value="">Válassz termékkategóriát</option>
                @foreach($productCategories as $pc)
                    <option value="{{ $pc->id }}" {{ old('product_category_id') == $pc->id ? 'selected' : '' }}>
                        {{ $pc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Termék neve</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="article_number" class="form-label">Cikkszám</label>
            <input type="text" name="article_number" id="article_number" class="form-control" value="{{ old('article_number') }}">
        </div>

        <div class="mb-3">
            <label for="manufacturer" class="form-label">Gyártó</label>
            <input type="text" name="manufacturer" id="manufacturer" class="form-control" value="{{ old('manufacturer') }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Leírás</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Ár</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}">
        </div>

        <div class="mb-3">
            <label for="currency" class="form-label">Valuta</label>
            <input type="text" name="currency" id="currency" class="form-control" value="{{ old('currency', 'HUF') }}">
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Raktáron</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', 0) }}">
        </div>

        <div class="mb-3">
            <label for="is_active" class="form-label">Állapot</label>
            <select name="is_active" id="is_active" class="form-select">
                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktív</option>
                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inaktív</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Kép</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
            <small class="text-muted">Ha van kép, töltsd fel (pl.: PNG, JPG).</small>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Mentés</button>
    </form>
</div>
@endsection
