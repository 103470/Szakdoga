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
            @php
                $rating = round($product->averageRating() ?? 0, 1);
            @endphp
            <p>
                <strong>Értékelés:</strong>
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($rating))
                        <i class="fa-solid fa-star text-warning"></i>
                    @elseif ($i - $rating < 1)
                        <i class="fa-solid fa-star-half-stroke text-warning"></i>
                    @else
                        <i class="fa-regular fa-star text-secondary"></i>
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

    {{-- ===== TAB RÉSZ ===== --}}
    <div class="container my-4">
        <ul class="nav product-tabs border-0" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab" aria-controls="desc" aria-selected="true">
                    Leírás
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="oem-tab" data-bs-toggle="tab" data-bs-target="#oem" type="button" role="tab" aria-controls="oem" aria-selected="false">
                    Gyári számok
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#productReviews" class="nav-link">
                    Vélemények ({{ $product->reviews->count() }})
                </a>
            </li>
        </ul>

        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="productTabsContent">
            {{-- Leírás --}}
            <div class="tab-pane fade show active" id="desc" role="tabpanel" aria-labelledby="desc-tab">
                @php
                    $description = $product->description ?? '';
                    preg_match_all('/([^:]+):\s*([^:]+)(?=\s+[A-ZÁÉÍÓÖŐÚÜŰ]|$)/u', $description, $matches, PREG_SET_ORDER);
                @endphp

                @if(!empty($matches))
                    <table class="product-specs alt-rows align-middle text-start" style="color: black;">
                        <tbody>
                            @foreach($matches as $match)
                                <tr>
                                    <th>{{ trim($match[1]) }}:</th>
                                    <td>{{ trim($match[2]) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color: black;">{{ $description }}</p>
                @endif
            </div>

            {{-- Gyári számok --}}
            <div class="tab-pane fade" id="oem" role="tabpanel" aria-labelledby="oem-tab">
                @if($product->oemNumbers->isEmpty())
                    <p class="text-dark">Nincsenek gyári számok a termékhez.</p>
                @else
                    <div class="table-responsive">
                        <table class="oem-table table-sm mb-0">
                            <tbody>
                                @php
                                    $cols = 4;
                                    $rows = ceil($product->oemNumbers->count() / $cols);
                                    $oemArray = $product->oemNumbers->values();
                                @endphp
                                @for($r = 0; $r < $rows; $r++)
                                    <tr>
                                        @for($c = 0; $c < $cols; $c++)
                                            @php $index = $r + $c * $rows; @endphp
                                            <td>
                                                @if(isset($oemArray[$index]))
                                                    {{ $oemArray[$index]->oem_number }}
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Vélemények --}}
    <div id="productReviews" class="product-reviews mt-5">
        <h4 class="fw-bold mb-3">Vásárlói vélemények</h4>

        @if ($product->reviews && $product->reviews->count() > 0)
            @foreach ($product->reviews as $review)
                <div class="card mb-3 review-card p-3">
                    <div class="d-flex align-items-center mb-2">
                        {{-- Profilkép vagy default ikon --}}
                        @if ($review->user && $review->user->profile_image)
                            <img src="{{ asset('storage/' . $review->user->profile_image) }}" 
                                alt="{{ $review->user->name }}" 
                                class="me-3 review-user-img">
                        @else
                            <div class="me-3 d-flex justify-content-center align-items-center review-user-img bg-light text-secondary">
                                <i class="fa-solid fa-user fa-lg"></i>
                            </div>
                        @endif

                        {{-- Felhasználónév és csillagok --}}
                        <div>
                            <strong>{{ $review->user->name ?? 'Ismeretlen felhasználó' }}</strong><br>
                            <div class="review-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-warning"></i>
                                @endfor
                                <span class="small text-muted ms-1">({{ $review->rating }}/5)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Vélemény tartalom --}}
                    <p class="mb-1"><strong>Miért választotta a terméket:</strong> {{ $review->reason ?? '-' }}</p>
                    <p class="review-pros mb-1"><strong>Előnyök:</strong> {{ $review->pros ?? '-' }}</p>
                    <p class="review-cons mb-0"><strong>Hátrányok:</strong> {{ $review->cons ?? '-' }}</p>
                </div>
            @endforeach
        @else
            <p class="text-muted">Ehhez a termékhez még nem érkezett vélemény.</p>
        @endif
    </div>
</div>
@endsection

