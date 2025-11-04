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
                    <a href="{{ route('termekcsoport_dynamic', [
                        'category' => $category->slug,
                        'subcategory' => $subcategory->slug
                    ]) }}">
                        {{ $subcategory->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $rareBrand->name }}
                </li>
            </ol>
        </nav>

        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            @if($rareBrand->logo)
                <img src="{{ asset('storage/' . $rareBrand->logo) }}" alt="{{ $rareBrand->name }}" class="img-fluid" style="max-height: 100px;">
            @endif
        </div>
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
                        <a href="{{ route('termekcsoport_vintage', [
                            'categorySlug' => $category->slug,
                            'subcategorySlug' => $subcategory->slug,
                            'productCategorySlug' => optional($productCategory)->slug,
                            'brandSlug' => $rareBrand->slug,
                            'typeSlug' => $type->slug
                        ]) }}" class="stretched-link"></a>
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="type-card-title fw-semibold" style="color: #3b5998;">
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
