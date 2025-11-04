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
                    <a href="{{ route('marka', ['slug' => $rareBrand->slug]) }}">{{ $rareBrand->name }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('tipus', ['brandSlug' => $rareBrand->slug, 'typeSlug' => $type->slug]) }}">{{ $type->name }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('model', ['brandSlug' => $rareBrand->slug, 'typeSlug' => $type->slug, 'vintageSlug' => $vintage->slug]) }}">{{ $vintage->frame }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('kategoria', [
                        'brandSlug' => $rareBrand->slug,
                        'typeSlug' => $type->slug,
                        'vintageSlug' => $vintage->slug,
                        'modelSlug' => $model->slug
                    ]) }}">{{ $model->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-3">
        <h2 class="border-start border-4 theme-blue-border ps-3 mb-1">
            {{ $rareBrand->name }} {{ $type->name }} ({{ $vintage->frame }})
            {{ $vintage->vintage_range }} {{ $model->unique_code }} {{ $category->name }}
        </h2>
    </div>

    <p class="text-muted mb-4">
        Kérjük válassza ki az alkategóriát!
    </p>

    @if($subcategories->isEmpty())
        <p class="text-center">Nincs elérhető alkategória ehhez a kategóriához.</p>
    @else
        <div class="row">
            @foreach($subcategories as $subcategory)
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="card type-card mb-2 text-center position-relative">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="type-card-title">{{ $subcategory->name }}</div>
                            <a href="{{ route('termekkategoria', [ 
                                'brandSlug' => $rareBrand->slug,
                                'typeSlug' => $type->slug,
                                'vintageSlug' => $vintage->slug,
                                'modelSlug' => $model->slug,
                                'categorySlug' => $category->slug,
                                'subcategorySlug' => $subcategory->slug
                            ]) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
