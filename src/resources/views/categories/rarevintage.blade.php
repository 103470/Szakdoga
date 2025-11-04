@extends('layouts.main')

@section('content')
<div class="body-container my-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">B+M Autóalkatrész</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('termekcsoport_dynamic', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}">{{ $category->name }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('termekcsoport_dynamic', ['category' => $category->slug, 'subcategory' => $subcategory->slug, 'brandSlug' => $rareBrand->slug]) }}">{{ $rareBrand->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $type->name }}</li>
            </ol>
        </nav>
        <a href="{{ url()->previous() }}" class="btn theme-blue-btn text-light">Vissza</a>
    </div>

    <div class="d-flex align-items-center mb-4">
        <h2 class="border-start border-4 theme-blue-border ps-3 mb-0">
            {{ $rareBrand->name }} {{ $type->name }}
        </h2>
    </div>

    <p class="text-muted mb-4">
        Kérjük, válassza ki gépjárműve megfelelő évjáratát!
    </p>

    <div class="table-responsive mt-4">
        <table class="table text-center mb-0 vintage-table">
            <thead class="table-dark">
                <tr>
                    <th>Név</th>
                    <th>Évjárat</th>
                    <th>Alváz</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vintages as $vintage)
                <tr style="cursor: pointer;"
                    onclick="window.location='{{ route('termekcsoport_model', [
                        'categorySlug' => $category->slug,
                        'subcategorySlug' => $subcategory->slug,
                        'productCategorySlug' => optional($productCategory)->slug,
                        'brandSlug' => $rareBrand->slug,
                        'typeSlug' => $type->slug,
                        'vintageSlug' => $vintage->slug 
                    ]) }}'">
                    <td>{{ $vintage->name }}</td>
                    <td>{{ $vintage->vintage_range }}</td>
                    <td>{{ $vintage->frame ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">Nincs elérhető évjárat ennél a ritka típusnál.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
