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
                    <a href="{{ route('marka', $rareBrand->slug) }}">{{ $rareBrand->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $type->name }}</li>
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-3">
        <div>
            <h2 class="border-start border-4 theme-blue-border ps-3 mb-1">
                {{ $rareBrand->name }} {{ $type->name }}
            </h2>
            <div class="mt-1">
                <small class="text-muted">
                    Kérjük, válassza ki gépjárműve megfelelő évjáratát!
                </small>
            </div>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table text-center mb-0 vintage-table">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Évjárat</th>
                    <th>Alváz</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vintages as $vintage)
                    <tr style="cursor: pointer;"
                        onclick="window.location='{{ route('model', [
                            'brandSlug' => $rareBrand->slug,
                            'typeSlug' => $type->slug,
                            'vintageSlug' => $vintage->slug
                        ]) }}'">
                        <td>{{ $vintage->name }}</td>
                        <td>
                            {{ $vintage->year_from }}.{{ str_pad($vintage->month_from, 2, '0', STR_PAD_LEFT) }}
                            -
                            {{ $vintage->year_to }}.{{ str_pad($vintage->month_to, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>{{ $vintage->frame ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Nincs elérhető évjárat ennél a típusnál.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
