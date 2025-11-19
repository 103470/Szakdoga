@extends('admin')

@section('content')
<div class="container">
    <h1>Új modell hozzáadása</h1>

    <a href="{{ route('admin.markak.modellek.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    <form action="{{ route('admin.markak.modellek.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="type_id" class="form-label">Típus</label>
            <select name="type_id" id="type_id" class="form-control" required>
                <option value="">Válassz típust...</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Modell neve</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Pl.: 320i" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="fuel_type_id" class="form-label">Üzemanyag típus</label>
            <select name="fuel_type_id" id="fuel_type_id" class="form-control">
                <option value="">Válassz üzemanyag típust...</option>
                @foreach($fuelTypes as $fuel)
                    <option value="{{ $fuel->id }}" {{ old('fuel_type_id') == $fuel->id ? 'selected' : '' }}>   
                        {{ ucfirst($fuel->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="frame" class="form-label">Frame</label>
            <select name="frame" id="frame" class="form-control">
                <option value="">Válassz frame-et...</option>
                @foreach($frames as $frame)
                    <option value="{{ $frame }}" {{ old('frame') == $frame ? 'selected' : '' }}>
                        {{ $frame }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="body_type" class="form-label">Body Type</label>
            <select name="body_type" id="body_type" class="form-control" required>
                <option value="">Válassz body type-ot...</option>
                @foreach($bodyTypes as $body)
                    <option value="{{ $body }}" {{ old('body_type') == $body ? 'selected' : '' }}>
                        {{ ucfirst($body) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col">
                <label for="ccm" class="form-label">Hengerűrtartalom (ccm)</label>
                <input type="number" name="ccm" id="ccm" class="form-control" placeholder="Pl.: 1998" value="{{ old('ccm') }}">
            </div>
            <div class="col">
                <label for="kw_hp" class="form-label">Teljesítmény (kW/LE)</label>
                <input type="text" name="kw_hp" id="kw_hp" class="form-control" placeholder="Pl.: 110/150" value="{{ old('kw_hp') }}">
            </div>
            <div class="col">
                <label for="engine_type" class="form-label">Motor típus</label>
                <input type="text" name="engine_type" id="engine_type" class="form-control" placeholder="Pl.: N20B20A" value="{{ old('engine_type') }}">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <label for="year_from" class="form-label">Évjárat kezdete (év)</label>
                <input type="number" name="year_from" id="year_from" class="form-control" placeholder="Pl.: 2015" min="1886" max="{{ now()->year }}" required value="{{ old('year_from') }}">
            </div>
            <div class="col">
                <label for="month_from" class="form-label">Hónap</label>
                <input type="number" name="month_from" id="month_from" class="form-control" placeholder="1-12" min="1" max="12" required value="{{ old('month_from') }}">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col">
                <label for="year_to" class="form-label">Évjárat vége (év)</label>
                <input type="number" name="year_to" id="year_to" class="form-control" placeholder="Pl.: 2020" min="1886" max="{{ now()->year }}" value="{{ old('year_to') }}">
            </div>
            <div class="col">
                <label for="month_to" class="form-label">Hónap</label>
                <input type="number" name="month_to" id="month_to" class="form-control" placeholder="1-12" min="1" max="12" value="{{ old('month_to') }}">
            </div>
        </div>

        <button type="submit" class="btn theme-blue-btn mt-3">Mentés</button>
    </form>
</div>
@endsection
