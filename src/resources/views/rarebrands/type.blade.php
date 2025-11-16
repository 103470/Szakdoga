@extends('layouts.main')

@section('content')
<div class="body-container my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">B+M Autóalkatrész</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $brand->name }}</li>
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-4">
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
            Nincs elérhető típus ennél a ritka márkánál.
        </div>
    @else
        <div class="row">
            @foreach($types as $type)
                <div class="col-md-4 col-sm-6 col-12 mb-3">
                    <div class="card type-card mb-2 text-center shadow-sm">
                        <a href="{{ route('tipus', [
                            'brandSlug' => $brand->slug,
                            'typeSlug' => $type->slug
                        ]) }}" class="stretched-link"></a>

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
