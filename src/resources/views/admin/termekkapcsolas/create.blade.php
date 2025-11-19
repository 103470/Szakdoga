@extends('admin')

@section('content')
<div class="container">
    <h1>Új termékkapcsolás létrehozása</h1>

    <a href="{{ route('admin.termekkapcsolas.index') }}" class="btn btn-secondary mb-3">← Vissza a listához</a>

    <form action="{{ route('admin.termekkapcsolas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">OEM szám</label>
            <select name="oem_number_id" class="form-select" required>
                <option value="">-- Válassz OEM számot --</option>
                @foreach($oemNumbers as $oem)
                    <option value="{{ $oem->id }}">{{ $oem->oem_number }} ({{ $oem->product->name ?? 'N/A' }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Model forrás</label>
            <select name="model_source" id="model_source" class="form-select" required>
                <option value="">-- Válassz típust --</option>
                <option value="brand">Brand</option>
                <option value="rarebrand">Rare Brand</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Model kód (unique_code)</label>
            <select name="unique_code" id="unique_code" class="form-select" required>
                <option value="">-- Előbb válassz model forrást --</option>
            </select>
        </div>

        <button type="submit" class="btn theme-blue-btn">Mentés</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sourceSelect = document.getElementById('model_source');
    const codeSelect = document.getElementById('unique_code');

    const brandCodes = @json($brandCodes);       
    const rareCodes = @json($rareBrandCodes);    

    sourceSelect.addEventListener('change', function () {
        const type = this.value;
        codeSelect.innerHTML = '<option value="">-- Válassz kódot --</option>';

        let list = [];
        if (type === 'brand') {
            list = brandCodes;
        } else if (type === 'rarebrand') {
            list = rareCodes;
        }

       list.forEach(item => {
            codeSelect.innerHTML += `<option value="${item.code}" data-source="${type}">${item.label}</option>`;
        });
    });
});
</script>
@endsection
