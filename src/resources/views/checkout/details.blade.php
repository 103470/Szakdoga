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
                <label for="shipping_email" class="form-label">Email</label>
                <input type="email" class="form-control" id="shipping_email" name="shipping_email"
                    value="{{ old('shipping_email', $user->shipping_email ?? $user->email ?? '') }}" required>
                @error('shipping_email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>


            <div class="mb-3">
                <label for="shipping_phone" class="form-label">Telefonszám</label>
                <div class="d-flex">
                    <select name="shipping_phone_prefix" id="shipping_phone_prefix" class="form-select me-2" style="max-width: 100px;" required>
                        @foreach($phonePrefixes as $prefix)
                            <option value="{{ $prefix->prefix }}" {{ (old('shipping_phone_prefix', $user->shipping_phone_prefix ?? '+36') == $prefix->prefix) ? 'selected' : '' }}>
                                {{ $prefix->prefix }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control" id="shipping_phone" name="shipping_phone"
                        value="{{ old('shipping_phone', $user->shipping_phone ?? '') }}" placeholder="70/435-44-55" required>
                </div>
                @error('shipping_phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_country" class="form-label">Ország</label>
                <input type="text" class="form-control" id="shipping_country" name="shipping_country"
                       value="{{ old('shipping_country', $user->shipping_country ?? '') }}" required>
                @error('shipping_country')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_zip" class="form-label">Irányítószám</label>
                <input type="text" class="form-control" id="shipping_zip" name="shipping_zip"
                       value="{{ old('shipping_zip', $user->shipping_zip ?? '') }}" required>
                @error('shipping_zip')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_city" class="form-label">Város</label>
                <input type="text" class="form-control" id="shipping_city" name="shipping_city"
                       value="{{ old('shipping_city', $user->shipping_city ?? '') }}" required>
                @error('shipping_city')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_street" class="form-label">Utca, házszám</label>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="shipping_street_name" name="shipping_street_name"
                        placeholder="Utcanév" value="{{ old('shipping_street_name', '') }}" required>
                    <input type="text" class="form-control" id="shipping_street_type" name="shipping_street_type"
                        placeholder="utca/körút/út" value="{{ old('shipping_street_type', '') }}" required>
                    <input type="text" class="form-control" id="shipping_house_number" name="shipping_house_number"
                        placeholder="Házszám" value="{{ old('shipping_house_number', '') }}" required>
                </div>
                @error('shipping_street_name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="shipping_building" class="form-label">Épület</label>
                <input type="text" class="form-control" id="shipping_building" name="shipping_building"
                    value="{{ old('shipping_building', '') }}">
            </div>

            <div class="mb-3">
                <label for="shipping_floor" class="form-label">Emelet</label>
                <input type="text" class="form-control" id="shipping_floor" name="shipping_floor"
                    value="{{ old('shipping_floor', '') }}">
            </div>

            <div class="mb-3">
                <label for="shipping_door" class="form-label">Ajtó</label>
                <input type="text" class="form-control" id="shipping_door" name="shipping_door"
                    value="{{ old('shipping_door', '') }}">
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
                <div class="d-flex">
                    <select name="billing_phone_prefix" id="billing_phone_prefix" class="form-select me-2" style="max-width: 100px;" required>
                        @foreach($phonePrefixes as $prefix)
                            <option value="{{ $prefix->prefix }}" {{ (old('billing_phone_prefix', $user->billing_phone_prefix ?? '+36') == $prefix->prefix) ? 'selected' : '' }}>
                                {{ $prefix->prefix }}
                            </option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control" id="billing_phone" name="billing_phone"
                        value="{{ old('billing_phone', $user->billing_phone ?? '') }}" placeholder="70/435-44-55" required>
                </div>
                @error('billing_phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

             <div class="mb-3">
                <label for="billing_country" class="form-label">Ország</label>
                <input type="text" class="form-control" id="billing_country" name="billing_country"
                       value="{{ old('billing_country', $user->billing_country ?? '') }}" required>
                @error('billing_country')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_zip" class="form-label">Irányítószám</label>
                <input type="text" class="form-control" id="billing_zip" name="billing_zip"
                       value="{{ old('billing_zip', $user->billing_zip ?? '') }}" required>
                @error('billing_zip')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_city" class="form-label">Város</label>
                <input type="text" class="form-control" id="billing_city" name="billing_city"
                       value="{{ old('billing_city', $user->billing_city ?? '') }}" required>
                @error('billing_city')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Utca, házszám</label>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="billing_street_name" name="billing_street_name" placeholder="Utcanév" value="{{ old('billing_street_name', '') }}" required>
                    <input type="text" class="form-control" id="billing_street_type" name="billing_street_type" placeholder="utca/körút/út" value="{{ old('billing_street_type', '') }}" required>
                    <input type="text" class="form-control" id="billing_house_number" name="billing_house_number" placeholder="Házszám" value="{{ old('billing_house_number', '') }}" required>
                </div>
                @error('billing_street_name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label for="billing_building" class="form-label">Épület</label>
                <input type="text" class="form-control" id="billing_building" name="billing_building" value="{{ old('billing_building', '') }}">
            </div>

            <div class="mb-3">
                <label for="billing_floor" class="form-label">Emelet</label>
                <input type="text" class="form-control" id="billing_floor" name="billing_floor" value="{{ old('billing_floor', '') }}">
            </div>

            <div class="mb-3">
                <label for="billing_door" class="form-label">Ajtó</label>
                <input type="text" class="form-control" id="billing_door" name="billing_door" value="{{ old('billing_door', '') }}">
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
