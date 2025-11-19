@extends('admin')

@section('content')
<div class="container">
    <h1>Évjárat szerkesztése – {{ $vintage->name }}</h1>
    <a href="{{ route('admin.ritkamarkak.evjaratok.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    <form action="{{ route('admin.ritkamarkak.evjaratok.update', $vintage->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="type_id" class="form-label">Típus</label>
            <select name="type_id" id="type_id" class="form-control" required>
                <option value="">Válassz típust...</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ $vintage->type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Évjárat neve</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Pl.: 3, A4" required value="{{ $vintage->name }}">
        </div>

        <div class="mb-3">
            <label for="frame" class="form-label">Frame</label>
            <input type="text" name="frame" id="frame" class="form-control" placeholder="Pl.: e46, 8EC" value="{{ $vintage->frame }}">
        </div>

        <div class="mb-3">
            <label for="body_type" class="form-label">Body Type</label>
            <select name="body_type" id="body_type" class="form-control" required>
                @foreach($bodyTypes as $body)
                    <option value="{{ $body }}" {{ $vintage->body_type == $body ? 'selected' : '' }}>{{ ucfirst($body) }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col">
                <label for="year_from" class="form-label">Évjárat kezdete (év)</label>
                <input type="number" name="year_from" id="year_from" class="form-control" placeholder="Pl.: 2020" min="1886" max="{{ now()->year }}" required value="{{ $vintage->year_from }}">
            </div>
            <div class="col">
                <label for="month_from" class="form-label">Hónap</label>
                <input type="number" name="month_from" id="month_from" class="form-control" placeholder="1-12" min="1" max="12" required value="{{ $vintage->month_from }}">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col">
                <label for="year_to" class="form-label">Évjárat vége (év)</label>
                <input type="number" name="year_to" id="year_to" class="form-control" placeholder="Pl.: 2023" min="1886" max="{{ now()->year }}" value="{{ $vintage->year_to }}">
            </div>
            <div class="col">
                <label for="month_to" class="form-label">Hónap</label>
                <input type="number" name="month_to" id="month_to" class="form-control" placeholder="1-12" min="1" max="12" value="{{ $vintage->month_to }}">
            </div>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Frissítés</button>
    </form>
</div>
@endsection
