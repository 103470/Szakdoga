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

                @if(isset($rareBrand))
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $rareBrand->name }}
                    </li>
                @endif
            </ol>
        </nav>

        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-4">
        <h2 class="border-start border-4 theme-blue-border ps-3 mb-0">
            {{ $rareBrand->name }}
        </h2>
    </div>

    <p class="text-muted mb-4">
        Kérjük, válassza ki gépjárműve megfelelő típusát!
    </p>

    @if($rareTypes->isEmpty())
        <div class="alert alert-info text-center">
            Nincs elérhető típus ennél a ritka márkánál.
        </div>
    @else
        <div class="row">
           @foreach($rareTypes as $type)
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="card type-card mb-2 text-center shadow-sm theme-rare-brand">
                        @php
                            if(!empty($productCategory)) {
                                $routeName = 'termekcsoport_vintage_pc';
                                $params = [
                                    'categorySlug' => $category->slug,
                                    'subcategorySlug' => $subcategory->slug,
                                    'productCategorySlug' => $productCategory->slug,
                                    'brandSlug' => $rareBrand->slug,
                                    'typeSlug' => $type->slug
                                ];
                            } else {
                                $routeName = 'termekcsoport_vintage';
                                $params = [
                                    'categorySlug' => $category->slug,
                                    'subcategorySlug' => $subcategory->slug,
                                    'brandSlug' => $rareBrand->slug,
                                    'typeSlug' => $type->slug
                                ];
                            }
                        @endphp
                        <a href="{{ route($routeName, $params) }}" class="stretched-link"></a>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="type-card-title fw-semibold" style="color: #2c2c2c;">
                                {{ $type->name }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @endif
</div>
@endsection
