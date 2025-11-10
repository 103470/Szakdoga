@extends('layouts.main')

@section('content')
<div class="body-container checkout-details-container my-5">

    <h3 class="text-center mb-5 ps-3">
        Szállítási és számlázási adatok
    </h3>

    <form action="{{ route('checkout.details.submit') }}" method="POST" id="checkout-form">
        @csrf

        <div class="card p-4 shadow-sm mb-4">
            <h4 class="mb-4">Szállítási adatok</h4>

            <div class="mb-3">
                <label for="shipping_name" class="form-label">Név</label>
                <input type="text" class="form-control" id="shipping_name" name="shipping_name"
                       value="{{ old('shipping_name', $user->shipping_name ?? $user->name ?? '') }}" required>
                @error('shipping_name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_phone" class="form-label">Telefonszám</label>
                <input type="text" class="form-control" id="shipping_phone" name="shipping_phone"
                       value="{{ old('shipping_phone', $user->shipping_phone ?? $user->phone ?? '') }}" required>
                @error('shipping_phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_address" class="form-label">Utca, házszám</label>
                <input type="text" class="form-control" id="shipping_address" name="shipping_address"
                       value="{{ old('shipping_address', $user->shipping_address ?? '') }}" required>
                @error('shipping_address')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_city" class="form-label">Város</label>
                <input type="text" class="form-control" id="shipping_city" name="shipping_city"
                       value="{{ old('shipping_city', $user->shipping_city ?? '') }}" required>
                @error('shipping_city')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_zip" class="form-label">Irányítószám</label>
                <input type="text" class="form-control" id="shipping_zip" name="shipping_zip"
                       value="{{ old('shipping_zip', $user->shipping_zip ?? '') }}" required>
                @error('shipping_zip')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_country" class="form-label">Ország</label>
                <input type="text" class="form-control" id="shipping_country" name="shipping_country"
                       value="{{ old('shipping_country', $user->shipping_country ?? '') }}" required>
                @error('shipping_country')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="card p-4 shadow-sm mb-4">
            <h4 class="mb-4">Számlázási adatok</h4>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="sameAsShipping">
                <label class="form-check-label" for="sameAsShipping">
                    A számlázási adataim megegyeznek a szállítási adataimmal
                </label>
            </div>

            <div class="mb-3">
                <label for="billing_name" class="form-label">Név</label>
                <input type="text" class="form-control" id="billing_name" name="billing_name"
                       value="{{ old('billing_name', $user->name ?? '') }}" required>
                @error('billing_name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="billing_email" name="billing_email"
                       value="{{ old('billing_email', $user->email ?? '') }}" required>
                @error('billing_email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_phone" class="form-label">Telefonszám</label>
                <input type="text" class="form-control" id="billing_phone" name="billing_phone"
                       value="{{ old('billing_phone', $user->phone ?? '') }}" required>
                @error('billing_phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_address" class="form-label">Utca, házszám</label>
                <input type="text" class="form-control" id="billing_address" name="billing_address"
                       value="{{ old('billing_address', $user->billing_address ?? '') }}" required>
                @error('billing_address')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_city" class="form-label">Város</label>
                <input type="text" class="form-control" id="billing_city" name="billing_city"
                       value="{{ old('billing_city', $user->billing_city ?? '') }}" required>
                @error('billing_city')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_zip" class="form-label">Irányítószám</label>
                <input type="text" class="form-control" id="billing_zip" name="billing_zip"
                       value="{{ old('billing_zip', $user->billing_zip ?? '') }}" required>
                @error('billing_zip')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_country" class="form-label">Ország</label>
                <input type="text" class="form-control" id="billing_country" name="billing_country"
                       value="{{ old('billing_country', $user->billing_country ?? '') }}" required>
                @error('billing_country')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold">
                Tovább a fizetésre
            </button>
        </div>
    </form>
</div>

@endsection
