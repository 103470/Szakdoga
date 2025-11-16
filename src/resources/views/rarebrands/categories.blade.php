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
                    <a href="{{ route('model', [
                        'brandSlug' => $rareBrand->slug,
                        'typeSlug' => $type->slug,
                        'vintageSlug' => $vintage->slug
                    ]) }}">{{ $vintage->frame }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $model->name }}</li>
            </ol>
        </nav>

        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-3">
        <h2 class="border-start border-4 theme-blue-border ps-3 mb-1">
            {{ $rareBrand->name }} {{ $type->name }} ({{ $vintage->frame }}) {{ $vintage->vintage_range }} {{ $model->unique_code }}
        </h2>
    </div>

    <p class="text-muted mb-4">
        Kérjük válassza ki a kategóriát!
    </p>

    <div class="mb-3">
        <input type="text" id="typeSearch" class="form-control" placeholder="Szűkítés...">
    </div>

    @if($categories->isEmpty())
        <p class="text-center">Nincs elérhető kategória ehhez a modellhez.</p>
    @else
        <div class="row">
            @foreach($categories->chunk(8) as $chunk)
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    @foreach($chunk as $category)
                        <div class="card type-card mb-2 text-center position-relative">
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div class="type-card-title">{{ $category->name }}</div>
                                <a href="{{ route('alkategoria', [
                                    'brandSlug' => $rareBrand->slug,
                                    'typeSlug' => $type->slug,
                                    'vintageSlug' => $vintage->slug,
                                    'modelSlug' => $model->slug,
                                    'categorySlug' => $category->slug
                                ]) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
