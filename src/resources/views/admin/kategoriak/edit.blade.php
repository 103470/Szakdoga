@extends('admin')

@section('content')
<div class="container">
    <h1>Kategória szerkesztése – {{ $category->name }}</h1>

    <a href="{{ route('admin.kategoriak.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.kategoriak.update', $category->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Kategória neve</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">Ikon</label>
            @if($category->icon)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" style="height:40px;">
                </div>
            @endif
            <input type="file" name="icon" id="icon" class="form-control" accept="image/*">
            <small class="text-muted">Ha van új ikon, töltsd fel (pl.: PNG, JPG).</small>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Mentés</button>
    </form>
</div>
@endsection
