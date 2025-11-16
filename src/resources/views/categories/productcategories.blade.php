@extends('layouts.main')

@section('content')
<div class="body-container my-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">B+M Autóalkatrész</a></li>
                <li class="breadcrumb-item"><a href="{{ route('termekcsoport', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $subcategory->name }}</li>
            </ol>

        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <h2 class="border-start border-4 theme-blue-border ps-3 mb-4">
        {{ $category->name }} / {{ $subcategory->name }}
    </h2>

    <p class="text-muted mb-4">
        Kérjük, válassza ki a termékkategóriát!
    </p>

    <div class="mb-3">
        <input type="text" id="typeSearch" class="form-control" placeholder="Szűkítés...">
    </div>

    @if($productCategories->isEmpty())
        <p class="text-center">Nincs elérhető termékkategória ehhez az alkategóriához.</p>
    @else
        <div class="row">
            @foreach($productCategories as $productCategory)
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="card type-card mb-2 text-center position-relative">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="type-card-title">{{ $productCategory->name }}</div>
                            <a href="{{ route('termekcsoport_brand', [
                                'category' => $category->slug,
                                'subcategory' => $subcategory->slug,
                                'productCategory' => $productCategory->slug
                            ]) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
