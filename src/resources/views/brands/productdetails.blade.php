@extends('layouts.main')

@section('content')
<div class="body-container my-5" style="max-width: 1000px;">
    <div class="text-end">
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn mb-3">← Vissza</a>
    </div>

    <h2 class="fw-bold mb-3">{{ $product->name }}</h2>

    <div class="row">
        <div class="col-md-5 text-center">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid border rounded shadow-sm p-2"
                 style="max-height: 250px;">
        </div>

        <div class="col-md-7">
            <p><strong>Cikkszám:</strong> {{ $product->article_number }}</p>
            <p><strong>Gyártó:</strong> {{ $product->manufacturer ?? 'Ismeretlen' }}</p>
            <p>
                <strong>Értékelés:</strong>
                @php $rating = $product->rating ?? 0; @endphp
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $rating)
                        <i class="bi bi-star-fill text-warning"></i>
                    @else
                        <i class="bi bi-star text-secondary"></i>
                    @endif
                @endfor
                <span class="small text-muted">({{ $rating }}/5)</span>
            </p>
            <p class="fw-bold text-danger fs-4">{{ number_format($product->price, 0, ',', ' ') }} Ft</p>
            <p class="text-success">
                @if($product->stock > 0)
                    Raktáron: {{ $product->stock }} db
                @else
                    Beszerezhető ({{ $product->delivery_time ?? '2-3 munkanap' }})
                @endif
            </p>
            <div class="d-flex align-items-center mb-3">
                <input type="number" class="form-control me-2" value="1" min="1" style="max-width: 100px;">
                <button class="btn btn-warning fw-bold text-dark">Kosárba</button>
            </div>
            @if ($brandModels && $brandModels->isNotEmpty())
                <div class="mt-3">
                    <label for="modelSelect" class="form-label fw-bold">
                        A termék az alábbi típusokhoz használható:
                    </label>
                    <select name="model" id="model" class="form-select">
                        @foreach ($brandModels->unique('id') as $model)
                            <option value="{{ $model->slug }}">
                                {{ $model->type->brand->name ?? '' }} 
                                {{ $model->type->name ?? '' }} 
                                ({{ $model->frame ?? '' }}) 
                                {{ $model->fuelType->name ?? '' }} 
                                {{ $model->name ?? '' }} 
                                {{ $model->year_from ?? '' }}/{{ $model->month_from ?? '01' }} - {{ $model->year_to ?? '' }}/{{ $model->month_to ?? '12' }} 
                                {{ $model->ccm ?? '' }} {{ $model->kw_hp ?? '' }} {{ $model->engine_type ?? '' }} 
                                ({{ $model->unique_code ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>
    <div class="mt-4">
        <h4 class="border-bottom pb-2">Leírás</h4>
        <p>{{ $product->description ?? 'Nincs részletes leírás a termékhez.' }}</p>
    </div>
</div>
@endsection
