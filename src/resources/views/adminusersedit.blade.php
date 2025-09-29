@extends('admin')

@section('title', 'Felhasználó szerkesztése')

@section('content')
<div class="container">
    <h1>Felhasználó szerkesztése</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tab navigáció --}}
        <ul class="nav nav-tabs" id="editTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="alap-tab" data-bs-toggle="tab" data-bs-target="#alap" type="button" role="tab">Alapadatok</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab">Számlázási adatok</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">Szállítási adatok</button>
            </li>
        </ul>

        <div class="tab-content mt-3" id="editTabsContent">

            {{-- Alapadatok --}}
            <div class="tab-pane fade show active" id="alap" role="tabpanel">
                <div class="mb-3">
                    <label class="form-label">Vezetéknév</label>
                    <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Keresztnév</label>
                    <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Fiók típusa</label>
                    <select name="account_type" class="form-select">
                        <option value="personal" @if($user->account_type=='personal') selected @endif>Személyes</option>
                        <option value="business" @if($user->account_type=='business') selected @endif>Céges</option>
                    </select>
                </div>
                <div class="mb-3 d-flex">
                    <input type="text" name="phone_country_code" value="{{ old('phone_country_code', $user->phone_country_code) }}" class="form-control me-2" placeholder="Országkód">
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-control" placeholder="Telefonszám">
                </div>
                <div class="mb-3">
                    <label class="form-label">Új jelszó (ha változtatni akar)</label>
                    <input type="password" name="password" class="form-control" placeholder="Jelszó">
                    <input type="password" name="password_confirmation" class="form-control mt-2" placeholder="Jelszó megerősítése">
                </div>
                <div class="mb-3">
                    <label class="form-label">Profilkép</label><br>
                    @if($user->profile_image)
                        <img src="{{ asset('storage/'.$user->profile_image) }}" alt="Profilkép" width="100" class="mb-2">
                    @endif
                    <input type="file" name="profile_image" class="form-control">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin"
                        value="1" {{ $user->is_admin ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin">
                                Admin jogosultság
                        </label>
                </div>

            </div>

            {{-- Számlázási adatok --}}
            <div class="tab-pane fade" id="billing" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ország</label>
                        <input type="text" name="billing_country" value="{{ old('billing_country', $user->billing_country) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Irányítószám</label>
                        <input type="text" name="billing_zip" value="{{ old('billing_zip', $user->billing_zip) }}" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Város</label>
                    <input type="text" name="billing_city" value="{{ old('billing_city', $user->billing_city) }}" class="form-control">
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Utca neve</label>
                        <input type="text" name="billing_street_name" value="{{ old('billing_street_name', $user->billing_street_name) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Közterület típusa</label>
                        <input type="text" name="billing_street_type" value="{{ old('billing_street_type', $user->billing_street_type) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Házszám</label>
                        <input type="text" name="billing_house_number" value="{{ old('billing_house_number', $user->billing_house_number) }}" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Épület</label>
                        <input type="text" name="billing_building" value="{{ old('billing_building', $user->billing_building) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Emelet</label>
                        <input type="text" name="billing_floor" value="{{ old('billing_floor', $user->billing_floor) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ajtó</label>
                        <input type="text" name="billing_door" value="{{ old('billing_door', $user->billing_door) }}" class="form-control">
                    </div>
                </div>
            </div>

            {{-- Szállítási adatok --}}
            <div class="tab-pane fade" id="shipping" role="tabpanel">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ország</label>
                        <input type="text" name="shipping_country" value="{{ old('shipping_country', $user->shipping_country) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Irányítószám</label>
                        <input type="text" name="shipping_zip" value="{{ old('shipping_zip', $user->shipping_zip) }}" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Város</label>
                    <input type="text" name="shipping_city" value="{{ old('shipping_city', $user->shipping_city) }}" class="form-control">
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Utca neve</label>
                        <input type="text" name="shipping_street_name" value="{{ old('shipping_street_name', $user->shipping_street_name) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Közterület típusa</label>
                        <input type="text" name="shipping_street_type" value="{{ old('shipping_street_type', $user->shipping_street_type) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Házszám</label>
                        <input type="text" name="shipping_house_number" value="{{ old('shipping_house_number', $user->shipping_house_number) }}" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Épület</label>
                        <input type="text" name="shipping_building" value="{{ old('shipping_building', $user->shipping_building) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Emelet</label>
                        <input type="text" name="shipping_floor" value="{{ old('shipping_floor', $user->shipping_floor) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ajtó</label>
                        <input type="text" name="shipping_door" value="{{ old('shipping_door', $user->shipping_door) }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success mt-3">Mentés</button>
    </form>
</div>
@endsection
