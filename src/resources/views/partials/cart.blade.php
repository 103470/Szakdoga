@extends('layouts.main')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Kosár</h3>

    <div class="row">
        <div class="col-lg-8 mb-4">
            @if($cart->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fa-solid fa-cart-shopping fa-3x mb-3"></i>
                    <p class="fs-5">Üres a kosarad</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Vásárlás folytatása</a>
                </div>
            @else
                @foreach ($cart as $item)
                    @php
                        $product = $item->product;
                    @endphp

                    <div class="cart-item card mb-3 shadow-sm p-3 d-flex flex-row align-items-center">
                        <img src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="me-3 rounded"
                            style="width: 100px; height: 100px; object-fit: cover;">

                        <div class="flex-grow-1">
                            <h5 class="mb-1">
                                <a href="{{ route('termek.leiras', ['product' => $product->slug]) }}"
                                class="text-decoration-none text-dark fw-semibold">
                                    {{ $product->name }}
                                </a>
                            </h5>

                            <small class="text-muted d-block">Cikkszám: {{ $product->article_number ?? 'N/A' }}</small>
                            <small class="text-muted d-block mb-2">Gyártó: {{ $product->manufacturer ?? 'Ismeretlen' }}</small>

                            <div class="quantity-selector d-inline-flex align-items-center" data-id="{{ $product->id }}">
                                <button class="btn btn-outline-secondary btn-sm quantity-decrease" data-id="{{ $product->id }}">−</button>
                                <input type="text"
                                    class="form-control form-control-sm text-center quantity-input mx-1"
                                    value="{{ $item->quantity }}" readonly
                                    data-id="{{ $product->id }}"
                                    data-max="{{ $product->stock ?? 1 }}"
                                    style="width: 60px;">
                                <button class="btn btn-outline-secondary btn-sm quantity-increase" data-id="{{ $product->id }}">+</button>
                                <button class="btn btn-outline-danger btn-sm ms-3 remove-item-btn" data-id="{{ $product->id }}">Töröl</button>
                            </div>
                        </div>

                        <div class="text-end fw-bold" style="min-width: 120px;" data-price="{{ $product->price }}">
                            {{ number_format($product->price * $item->quantity, 0, ',', ' ') }} Ft
                        </div>
                    </div>

                @endforeach

            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Rendelés összegzése</h5>

                    @php
                        $subtotal = 0;
                    @endphp

                    @foreach ($cart as $item)
                        @php
                            $product = $item->product;
                            $itemTotal = $product->price * $item->quantity;
                            $subtotal += $itemTotal;
                        @endphp
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ $item->quantity }} × {{ $product->name }}</span>
                            <span>{{ number_format($itemTotal, 0, ',', ' ') }} Ft</span>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
                        <span>Végösszeg:</span>
                        <span id="cart-total">{{ number_format($subtotal, 0, ',', ' ') }} Ft</span>
                    </div>

                    <a href="{{ route('checkout.choice') }}" class="btn theme-blue-btn w-100 fw-bold py-2 text-light">
                        Folytatás
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
