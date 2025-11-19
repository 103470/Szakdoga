@extends('admin')

@section('content')
<div class="container">
    <h1>Új OEM szám</h1>

    <a href="{{ route('admin.oemszamok.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.oemszamok.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">OEM szám</label>
            <input 
                type="text" 
                name="oem_number" 
                class="form-control" 
                value="{{ old('oem_number') }}" 
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Termék</label>
            <select name="product_id" class="form-select" required>
                <option value=""> -- válassz terméket -- </option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
