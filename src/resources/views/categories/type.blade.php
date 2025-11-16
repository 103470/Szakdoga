@extends('layouts.main')

@section('content')
<div class="body-container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="stretched-link">B+M Autóalkatrész</a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('termekcsoport', ['category' => $category->slug]) }}" class="stretched-link">
                        {{ $category->name }}
                    </a>
                </li>

                @if(isset($subcategory))
                    <li class="breadcrumb-item">
                        <a href="{{ route('termekcsopor_productCategory', [
                            'category' => $category->slug,
                            'subcategory' => $subcategory->slug
                        ]) }}" class="stretched-link">
                            {{ $subcategory->name }}
                        </a>
                    </li>
                @endif

                @if(!empty($productCategory))
                    <li class="breadcrumb-item">
                        <a href="{{ route('termekcsoport_brand', [
                            'category' => $category->slug,
                            'subcategory' => $subcategory->slug,
                            'productCategory' => $productCategory->slug
                        ]) }}" class="stretched-link">
                            {{ $productCategory->name }}
                        </a>
                    </li>
                @endif

                @if(isset($brand))
                    <li class="breadcrumb-item active" aria-current="page">{{ $brand->name }}</li>
                @endif
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid" style="max-height: 100px;">
        </div>
        <h2 class="border-start border-4 theme-blue-border ps-3 mb-0">
            {{ $brand->name }}
        </h2>
    </div>

    <p class="text-muted mb-4">
        Kérjük, válassza ki gépjárműve megfelelő típusát!
    </p>

    <div class="mb-3">
        <input type="text" id="typeSearch" class="form-control" placeholder="Szűkítés...">
    </div>

    @if($types->isEmpty())
        <div class="alert alert-info text-center">
            Nincs elérhető típus ennél a márkánál.
        </div>
    @else
        <div class="row">
            @foreach($types as $type)
                @php
                    if (!empty($productCategory)) {
                        // Ha van productCategory, a hosszabb route-ot használjuk
                        $params = [
                            'categorySlug' => $category->slug,
                            'subcategorySlug' => $subcategory->slug,
                            'productCategorySlug' => $productCategory->slug,
                            'brandSlug' => $brand->slug,
                            'typeSlug' => $type->slug
                        ];
                        $routeName = 'termekcsoport_vintage_pc';
                    } else {
                        // Ha nincs productCategory, a rövidebb route-ot használjuk
                        $params = [
                            'categorySlug' => $category->slug,
                            'subcategorySlug' => $subcategory->slug,
                            'brandSlug' => $brand->slug,
                            'typeSlug' => $type->slug
                        ];
                        $routeName = 'termekcsoport_vintage';
                    }
                @endphp
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="card type-card mb-2 text-center shadow-sm">
                        <a href="{{ route($routeName, $params) }}" class="stretched-link"></a>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="type-card-title fw-semibold">{{ $type->name }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
