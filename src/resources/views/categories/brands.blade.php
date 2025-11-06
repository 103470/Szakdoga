@extends('layouts.main')

@section('content')
<div class="body-container my-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="stretched-link">B+M Autóalkatrész</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('termekcsoport', ['category' => $category->slug]) }}" class="stretched-link">
                        {{ $category->name }}
                    </a>
                </li>

                @if(isset($subcategory))
                    <li class="breadcrumb-item">
                        <a href="{{ route('termekcsoport_dynamic', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}" class="stretched-link">
                            {{ $subcategory->name }}
                        </a>
                    </li>
                @endif

                 @if(!empty($productCategory))
                    <li class="breadcrumb-item">
                        <a href="{{ route('termekcsoport_dynamic', [
                            'category' => $category->slug,
                            'subcategory' => $subcategory->slug,
                            'productCategory' => $productCategory->slug
                        ]) }}" class="stretched-link">
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
        @forelse($brands as $brand)
            @php
                $params = [$category->slug, $subcategory->slug];
                if (isset($productCategory)) {
                    $params[] = $productCategory->slug;
                }
                $params[] = $brand->slug;
            @endphp
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card brand-card text-center h-100">
                    <a href="{{ route('termekcsoport_dynamic', $params) }}" class="stretched-link"></a>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        @if($brand->logo)
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid mb-2" style="max-height: 80px;">
                        @endif
                        <div class="fw-bold">{{ $brand->name }}</div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Nincs elérhető márka.</p>
        @endforelse
    </div>

    <h4 class="mb-3">Ritka márkák</h4>
    <div class="row">
        @forelse($rareBrands as $brand)
            @php
                $params = [$category->slug, $subcategory->slug];
                if (isset($productCategory)) {
                    $params[] = $productCategory->slug;
                }
                $params[] = $brand->slug;
            @endphp
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card brand-card type-card theme-rare-brand text-center h-100">
                    <a href="{{ route('termekcsoport_dynamic', $params) }}" class="stretched-link"></a>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div class="fw-bold" style="color: #3b5998;">{{ $brand->name }}</div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Nincs elérhető ritka márka.</p>
        @endforelse
    </div>
</div>
@endsection
