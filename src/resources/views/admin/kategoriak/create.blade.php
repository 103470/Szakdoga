@extends('admin')

@section('content')
<div class="container">
    <h1>Új kategória hozzáadása</h1>

    <a href="{{ route('admin.kategoriak.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.kategoriak.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Kategória neve</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Pl.: Olaj" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="icon" class="form-label">Ikon</label>
            <input type="file" name="icon" id="icon" class="form-control" accept="image/*">
            <small class="text-muted">Ha van ikon, töltsd fel (pl.: PNG, JPG).</small>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Mentés</button>
    </form>
</div>
@endsection
