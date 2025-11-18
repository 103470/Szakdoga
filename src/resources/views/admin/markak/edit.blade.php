@extends('admin') 

@section('content')
<div class="container mt-4">
    <h1>Márka szerkesztése</h1>

    <a href="{{ route('admin.markak.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.markak.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') 

        <div class="mb-3">
            <label for="name" class="form-label">Márka neve</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $brand->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Logo (opcionális)</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">

            @if($brand->logo)
                <div class="mt-2">
                    <p>Jelenlegi logo:</p>
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" width="100">
                </div>
            @endif
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
