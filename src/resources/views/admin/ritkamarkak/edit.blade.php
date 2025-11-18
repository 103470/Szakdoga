@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Ritka márka szerkesztése</h1>

    <form action="{{ route('admin.ritkamarkak.update', $rareBrand->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Név</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $rareBrand->name) }}" required>
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>
@endsection
