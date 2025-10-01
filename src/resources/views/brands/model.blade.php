@extends('layouts.main')
@section('content')
<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">B+M Autóalkatrész</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marka', $brand->slug) }}">{{ $brand->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tipus', ['brandSlug' => $brand->slug, 'typeSlug' => $type->slug]) }}">{{ $type->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $vintage->name }} {{ $vintage->frame }} {{ $vintage->vintage_range }}
                </li>
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-3">
        <div class="me-3">
            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="img-fluid" style="max-height: 100px;">
        </div>
        <div>
            <h2 class="border-start border-4 theme-blue-border ps-3 mb-1">
                {{ $brand->name }} {{ $type->name }} {{ $vintage->vintage_range }}
            </h2>
            <div class="mt-1">
                <small class="text-muted">Kérjük válassza ki gépjárműve pontos modelljét!</small>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-4">
        @if(collect($groupedModels)->isEmpty())
            <div class="alert alert-info">Nincs elérhető modell adat ennél az évjáratnál.</div>
        @else
            <table class="table text-center mb-0 vintage-table">
                <thead>
                    <tr>
                        <th>Típus</th>
                        <th>Évjárat</th>
                        <th>Ccm</th>
                        <th>kW / LE</th>
                        <th>Motor típus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedModels as $fuel => $models)
                        @if($models->isNotEmpty())
                            <tr>
                                <td colspan="5" class="text-start"><strong>{{ $fuel }}</strong></td>
                            </tr>
                            @foreach($models as $model)
                            <tr onclick="window.location='{{ url('tipus', [$brand->slug, $type->slug, $vintage->slug, $model->slug]) }}';" style="cursor:pointer;">
                                <td>{{ $model->name }}</td>
                                <td>{{ $model->year_range }}</td>
                                <td>{{ $model->ccm_formatted }}</td>
                                <td>{{ $model->kw_le_formatted }}</td>
                                <td>
                                    {{ $model->engine_type }}
                                    @if($model->unique_code)
                                        ({{ $model->unique_code }})
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
