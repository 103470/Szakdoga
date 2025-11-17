@extends('layouts.main')

@section('content')
<div class="body-container my-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">B+M Autóalkatrész</a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('termekcsoport', ['category' => $category->slug]) }}">
                        {{ $category->name }}
                    </a>
                </li>

                @if(isset($subcategory))
                    <li class="breadcrumb-item">
                        <a href="{{ route('termekcsopor_productCategory', [
                            'category' => $category->slug,
                            'subcategory' => $subcategory->slug
                        ]) }}">
                            {{ $subcategory->name }}
                        </a>
                    </li>
                @endif

                @if(isset($productCategory))
                    <li class="breadcrumb-item">
                        <a href="{{ route('termekcsopor_productCategory', [
                            'category' => $category->slug,
                            'subcategory' => $subcategory->slug
                        ]) }}">
                            {{ $productCategory->name }}
                        </a>
                    </li>
                @endif
            </ol>

        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <h2 class="border-start border-4 theme-blue-border ps-3 mb-4">
        {{ $subcategory->name }} - Márkaválasztó
    </h2>

    <p class="text-muted mb-4">
        Kérjük, válassza ki a kívánt márkát!
    </p>

    <h4 class="mb-3">Márkák</h4>
    <div class="row mb-5">
        @foreach($brands as $brand)
            @php
                // Alapértelmezés: nincs route
                $routeName = null;
                $params = [];

                // Ha van productCategory
                if(isset($productCategory) && $brand->slug) {
                    $routeName = 'termekcsoport_dynamic_pc';
                    $params = [
                        'category' => $category->slug,
                        'subcategory' => $subcategory->slug,
                        'productCategory' => $productCategory->slug,
                        'brandSlug' => $brand->slug
                    ];
                } 
                // Ha a kategória igényli a modellt és van brandSlug
                elseif(!isset($productCategory) && $category->requires_model && $brand->slug) {
                    $routeName = 'termekcsoport_dynamic';
                    $params = [
                        'category' => $category->slug,
                        'subcategory' => $subcategory->slug,
                        'brandSlug' => $brand->slug
                    ];
                }
            @endphp

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card brand-card text-center h-100">
                    @if($routeName)
                        <a href="{{ route($routeName, $params) }}" class="stretched-link"></a>
                    @endif
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid mb-2" style="max-height: 80px;">
                        @endif
                        <div class="fw-bold">{{ $brand->name }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h4 class="mb-3">Ritka márkák</h4>
    <div class="row">
        @foreach($rareBrands as $brand)
            @if(isset($productCategory))
                {{-- productCategory + brand --}}
                @php
                    $routeName = 'termekcsoport_dynamic_pc';
                    $params = [
                        'category' => $category->slug,
                        'subcategory' => $subcategory->slug,
                        'productCategory' => $productCategory->slug,
                        'brandSlug' => $brand->slug
                    ];
                @endphp
            @else
                {{-- csak brand --}}
                @php
                    $routeName = 'termekcsoport_dynamic';
                    $params = [
                        'category' => $category->slug,
                        'subcategory' => $subcategory->slug,
                        'brandSlug' => $brand->slug
                    ];
                @endphp
            @endif

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card brand-card type-card theme-rare-brand text-center h-100">
                    <a href="{{ route($routeName, $params) }}" class="stretched-link"></a>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="fw-bold" style="color: #2c2c2c;">{{ $brand->name }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
