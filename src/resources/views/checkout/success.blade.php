@extends('layouts.main')

@section('content')
<div class="body-container checkout-details-container my-5">
    <div class="text-center">
        <h3 class="mb-4">Köszönjük a rendelésed!</h3>

        <p class="mb-4">
            A rendelésed sikeresen leadva. A rendelési azonosítód:
        </p>

        <h4 class="mb-4 text-primary">
            #{{ $order->id }}
        </h4>

        <p class="mb-4">
            A rendelés részleteit e-mailben is elküldtük neked a megadott címre.
        </p>

        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5 fw-bold">
            Vissza a főoldalra
        </a>
    </div>
</div>
@endsection
