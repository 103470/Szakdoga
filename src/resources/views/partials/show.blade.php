@extends('layouts.main')

@section('content')
<div class="product-list">
    @foreach([$product] as $p) {{-- show oldalon csak 1 termék --}}
        <div class="card mb-3 shadow-sm p-3">
            <div class="row g-0 align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ asset('storage/' . $p->image) ?? asset('images/no-image.png') }}" 
                        alt="{{ $p->name }}" 
                        class="img-fluid rounded"
                        style="max-height:150px; object-fit:contain;">
                </div>

                <div class="col-md-6">
                    <div class="card-body">
                        <h5 class="fw-bold text-uppercase text-danger mb-1">{{ $p->name }}</h5>
                        <p class="text-muted mb-1">Cikkszám: <strong>{{ $p->article_number }}</strong></p>
                        <p class="mb-2">Gyártó: <strong>{{ $p->manufacturer ?? 'Ismeretlen' }}</strong></p>

                        <div class="mb-2">
                            @php $rating = round($p->averageRating() ?? 0, 1); @endphp
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
                        </div>

                        <p class="text-muted small">{{ $p->description }}</p>
                    </div>
                </div>

                <div class="col-md-3 text-end">
                    <div class="p-3">
                        <p class="fw-bold text-danger fs-5 mb-1">{{ number_format($p->price, 0, ',', ' ') }} Ft</p>

                        <div class="input-group quantity-selector mb-2" style="max-width: 120px; float: right;">
                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-decrease text-light">-</button>
                            <input type="number" class="form-control text-center quantity-input"
                                   value="1" min="1" max="{{ $p->stock }}" readonly>
                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-increase text-light">+</button>
                        </div>

                        <button class="btn btn-warning w-100 fw-bold text-dark add-to-cart-btn" data-id="{{ $p->id }}">
                            Kosárba
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection
