@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Előhívószám szerkesztése</h1>

    <a href="{{ route('admin.elohivoszamok.index') }}" class="btn btn-secondary mb-3">
        ← Vissza a listához
    </a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.elohivoszamok.update', $prefixItem->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="prefix" class="form-label">Előhívószám</label>
            <input type="text" name="prefix" id="prefix" class="form-control"
                   value="{{ old('prefix', $prefixItem->prefix) }}" required>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Ország</label>
            <input type="text" name="country" id="country" class="form-control"
                   value="{{ old('country', $prefixItem->country) }}" required>
        </div>

        <button type="submit" class="btn theme-blue-btn">Frissítés</button>
    </form>
</div>
@endsection
