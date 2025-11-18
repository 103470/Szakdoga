@extends('admin')

@section('content')
<div class="container">
    <h1>Típus szerkesztése – {{ $type->name }}</h1>

    <a href="{{ route('admin.ritkamarkak.tipusok.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    <form action="{{ route('admin.ritkamarkak.tipusok.update', $type->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="brand_id" class="form-label">Ritka márka</label>
            <select name="brand_id" id="brand_id" class="form-control" required>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $type->rare_brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>

                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Típus neve</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $type->name }}" required>
        </div>

        <button type="submit" class="btn theme-blue-btn">Frissítés</button>
    </form>
</div>
@endsection
