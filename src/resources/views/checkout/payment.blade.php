@extends('layouts.main')

@section('content')
<div class="container my-5">

    <form action="{{ route('checkout.finalize') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm p-4 mb-4">
                    <h4 class="mb-3">Átvételi mód</h4>
                    <div class="mt-3">
                        @foreach ($deliveryOptions as $option)
                            <div class="form-check mb-3">
                                <input class="form-check-input" 
                                    type="radio" 
                                    name="delivery_option" 
                                    id="delivery_{{ $option->id }}" 
                                    value="{{ $option->id }}" 
                                    data-price="{{ $option->price ?? 0 }}"
                                    {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="delivery_{{ $option->id }}">
                                    {{ $option->name }}
                                    @if($option->price > 0)
                                        <span class="text-muted">(+{{ number_format($option->price, 0, ',', ' ') }} Ft)</span>
                                    @else
                                        <span class="text-success">(Ingyenes)</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card shadow-sm p-4 mb-4">
                    <h4 class="mb-3">Fizetési mód</h4>
                    <div class="mt-3">
                        @foreach ($paymentOptions as $option)
                            <div class="form-check mb-3">
                                <input class="form-check-input" 
                                    type="radio" 
                                    name="payment_option" 
                                    id="payment_{{ $option->id }}" 
                                    value="{{ $option->id }}"
                                    data-fee="{{ $option->fee ?? 0 }}"
                                    {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="payment_{{ $option->id }}">
                                    {{ $option->name }}
                                    @if($option->fee > 0)
                                        <span class="text-muted">(+{{ number_format($option->fee, 0, ',', ' ') }} Ft)</span>
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm p-4">
                    <h5 class="mb-4">Rendelés összegzése</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Termékek összesen:</span>
                        <span id="subtotal-value" data-subtotal="{{ $subtotal }}">{{ number_format($subtotal, 0, ',', ' ') }} Ft</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Szállítás:</span>
                        <span id="shipping-cost">0 Ft</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Fizetési díj:</span>
                        <span id="payment-fee">0 Ft</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Végösszeg:</span>
                        <span id="total">{{ number_format($subtotal, 0, ',', ' ') }} Ft</span>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 mt-4 fw-bold py-2">
                        Rendelés véglegesítése
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
