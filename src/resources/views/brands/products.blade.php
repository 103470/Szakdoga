@extends('layouts.main')

@section('content')
<div class="body-container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">B+M Autóalkatrész</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marka', ['slug' => $brand->slug]) }}">{{ $brand->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tipus', ['brandSlug' => $brand->slug, 'typeSlug' => $type->slug]) }}">{{ $type->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('model', ['brandSlug' => $brand->slug, 'typeSlug' => $type->slug, 'vintageSlug' => $vintage->slug]) }}">{{ $vintage->frame }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kategoria', ['brandSlug' => $brand->slug, 'typeSlug' => $type->slug, 'vintageSlug' => $vintage->slug, 'modelSlug' => $model->slug]) }}">{{ $model->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kategoria', ['brandSlug' => $brand->slug, 'typeSlug' => $type->slug, 'vintageSlug' => $vintage->slug, 'modelSlug' => $model->slug, 'categorySlug' => $category->slug]) }}">{{ $category->name }}</a></li>
                @if(isset($subcategory))
                    <li class="breadcrumb-item"><a href="{{ route('alkategoria', ['brandSlug' => $brand->slug, 'typeSlug' => $type->slug, 'vintageSlug' => $vintage->slug, 'modelSlug' => $model->slug, 'categorySlug' => $category->slug, 'subcategorySlug' => $subcategory->slug]) }}">{{ $subcategory->name }}</a></li>
                @endif
                @if(isset($productCategory))
                    <li class="breadcrumb-item active" aria-current="page">{{ $productCategory->name }}</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">Összes termék</li>
                @endif
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <h2 class="mb-4">
        @if(isset($productCategory))
            {{ $subcategory->name }} – {{ $productCategory->name }} termékkínálatunkból
        @elseif(isset($subcategory))
            {{ $subcategory->name }} termékkínálatunkból
        @else
            {{ $category->name }} – Termékek
        @endif
    </h2>

    @if($products->isEmpty())
        <p class="text-center">Ehhez a kategóriához jelenleg nincs elérhető termék.</p>
    @else
        <div class="product-list">
            @foreach($products as $product)
                <div class="card mb-3 shadow-sm p-3">
                    <div class="row g-0 align-items-center">
                        {{-- Kép --}}
                        <div class="col-md-3 text-center">
                            <img src="{{ asset('storage/' . $product->image) ?? asset('images/no-image.png') }}" 
                                alt="{{ $product->name }}" 
                                class="img-fluid rounded" style="max-height:150px;">
                        </div>

                        {{-- Termékinformációk --}}
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="fw-bold text-uppercase text-danger mb-1">
                                    <a href="{{ route('termek.leiras', ['product' => $product->slug]) }}" 
                                        class="text-danger text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-1">Cikkszám: <strong>{{ $product->article_number }}</strong></p>
                                <p class="mb-2">Gyártó: <strong>{{ $product->manufacturer ?? 'Ismeretlen' }}</strong></p>
                                
                                {{-- Értékelés (csillagok) --}}
                                <div class="mb-2">
                                    @php
                                        $rating = round($product->averageRating() ?? 0, 1); // átlagértékelés, pl. 3.5
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($rating))
                                            <i class="fa-solid fa-star text-warning"></i> {{-- Teljes csillag --}}
                                        @elseif ($i - $rating < 1)
                                            <i class="fa-solid fa-star-half-stroke text-warning"></i> {{-- Fél csillag --}}
                                        @else
                                            <i class="fa-regular fa-star text-secondary"></i> {{-- Üres csillag --}}
                                        @endif
                                    @endfor
                                    <span class="small text-muted">({{ $rating }}/5)</span>
                                </div>


                                {{-- Rövid leírás --}}
                                <p class="text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $product->description }}
                                </p>
                            </div>
                        </div>

                        {{-- Ár és kosár --}}
                        <div class="col-md-3 text-end">
                            <div class="p-3">
                                <p class="fw-bold text-danger fs-5 mb-1">{{ number_format($product->price, 0, ',', ' ') }} Ft</p>
                                <p class="small text-success mb-2">
                                    @if($product->stock > 0)
                                        Raktáron: {{ $product->stock }} db
                                    @else
                                        Beszerezhető ({{ $product->delivery_time ?? '2-3 munkanap' }})
                                    @endif
                                </p>

                               <div class="input-group quantity-selector mb-2" style="max-width: 120px; float: right;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-decrease text-light">-</button>
                                    <input type="number"
                                        class="form-control text-center quantity-input"
                                        value="1"
                                        min="1"
                                        max="{{ $product->stock }}"
                                        readonly>
                                    <button type="button" class="btn btn-outline-secondary btn-sm quantity-increase text-light">+</button>
                                </div>

                                <button class="btn btn-warning w-100 fw-bold text-dark">Kosárba</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
