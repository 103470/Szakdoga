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
                                <a href="{{ route('marka', $brand->slug) }}">
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

<div class="container my-5">
    <h2 class="text-center mb-4">Kevésbé gyakori gyártmányok</h2>

    <div id="rareBrandCarousel" class="carousel slide" data-bs-ride="carousel">
        
        <div class="carousel-indicators">
            @foreach($rareBrands->chunk(30) as $chunkIndex => $chunk)
                <button type="button" 
                        data-bs-target="#rareBrandCarousel" 
                        data-bs-slide-to="{{ $chunkIndex }}" 
                        class="{{ $chunkIndex == 0 ? 'active' : '' }}" 
                        aria-current="{{ $chunkIndex == 0 ? 'true' : 'false' }}" 
                        aria-label="Slide {{ $chunkIndex + 1 }}"></button>
            @endforeach
        </div>

        
        <div class="carousel-inner">
            @foreach($rareBrands->chunk(30) as $chunkIndex => $chunk)
                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                    <div class="table-responsive">
                        <table class="table table-borderless text-center mb-0">
                            <tbody>
                                @php
                                    $rows = 5;
                                    $cols = 6;
                                    $chunkArray = $chunk->values(); 
                                @endphp
                                @for($r = 0; $r < $rows; $r++)
                                    <tr>
                                        @for($c = 0; $c < $cols; $c++)
                                            @php
                                                $index = $r + $c * $rows;
                                            @endphp
                                            <td>
                                                @if(isset($chunkArray[$index]))
                                                    <a href="{{ route('marka', $chunkArray[$index]->slug) }}" class="text-decoration-none text-dark">
                                                        {{ $chunkArray[$index]->name }}
                                                    </a>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        
        <button class="carousel-control-prev" type="button" data-bs-target="#rareBrandCarousel" data-bs-slide="prev">
            <span class="fa-solid fa-chevron-left fa-2x"></span>
            <span class="visually-hidden">Előző</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#rareBrandCarousel" data-bs-slide="next">
            <span class="fa-solid fa-chevron-right fa-2x"></span>   
            <span class="visually-hidden">Következő</span>
        </button>
    </div>
</div>


<div class="container my-4">
    <h2 class="mb-4 text-center">Termékcsoportok</h2>

    <div id="categoryCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($categories->chunk(12) as $chunkIndex => $chunk)
                <button type="button" 
                        data-bs-target="#categoryCarousel" 
                        data-bs-slide-to="{{ $chunkIndex }}" 
                        class="{{ $chunkIndex == 0 ? 'active' : '' }}" 
                        aria-current="{{ $chunkIndex == 0 ? 'true' : 'false' }}" 
                        aria-label="Slide {{ $chunkIndex + 1 }}"></button>
            @endforeach
        </div>

        <div class="carousel-inner">
            @foreach($categories->chunk(12) as $chunkIndex => $chunk)
                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                    <div class="d-flex flex-wrap justify-content-start">
                        @foreach($chunk as $category)
                            <div class="brand-card">
                                <a href="{{ route('termekcsoport', $category->slug) }}">
                                    <div class="card text-center p-2 h-100">
                                        <div class="d-flex justify-content-center align-items-center brand-logo">
                                            <img src="{{ asset('storage/'.$category->icon) }}" 
                                                 alt="{{ $category->name }}">
                                        </div>
                                        <h6 class="mt-2">{{ $category->name }}</h6>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
            <span class="fa-solid fa-chevron-left fa-2x"></span>
            <span class="visually-hidden">Előző</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
            <span class="fa-solid fa-chevron-right fa-2x"></span>
            <span class="visually-hidden">Következő</span>
        </button>
    </div>
</div>

@endsection
