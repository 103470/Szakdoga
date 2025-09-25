@extends('layouts.main')
@section('content')
<div class="container my-4">
    <h2 class="mb-4 text-center">Válassz márkát</h2>

    <div id="brandCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($brands->chunk(12) as $chunkIndex => $chunk)
                <button type="button" 
                        data-bs-target="#brandCarousel" 
                        data-bs-slide-to="{{ $chunkIndex }}" 
                        class="{{ $chunkIndex == 0 ? 'active' : '' }}" 
                        aria-current="{{ $chunkIndex == 0 ? 'true' : 'false' }}" 
                        aria-label="Slide {{ $chunkIndex + 1 }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach($brands->chunk(12) as $chunkIndex => $chunk)
                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                    <div class="d-flex flex-wrap justify-content-start">
                        @foreach($chunk as $brand)
                            <div class="brand-card">
                                <a href="{{ route('tipus', $brand->slug) }}">
                                    <div class="card text-center p-2 h-100">
                                        <div class="d-flex justify-content-center align-items-center brand-logo">
                                            <img src="{{ asset('storage/'.$brand->logo) }}" 
                                                 alt="{{ $brand->name }}">
                                        </div>
                                        <h6 class="mt-2">{{ $brand->name }}</h6>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#brandCarousel" data-bs-slide="prev">
            <span class="fa-solid fa-chevron-left fa-2x"></span>
            <span class="visually-hidden">Előző</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#brandCarousel" data-bs-slide="next">
            <span class="fa-solid fa-chevron-right fa-2x"></span>
            <span class="visually-hidden">Következő</span>
        </button>
    </div>
</div>

@endsection
