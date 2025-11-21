@extends('userlayouts')

@section('title', 'Felhasználói profil')

@section('content')
<style>
    body { background-color: #f5f5f5; color: #333; }
    .nav-tabs .nav-link { color: #555; }
    .nav-tabs .nav-link.active { background-color: #2c2c2c; color: #fff; }
    .nav-tabs .nav-link:hover { background-color: #1a1a1a; color: #fff; }
    .btn-custom { background-color: #2c2c2c; color: #fff; font-size: 0.9rem; padding: 6px 12px; }
    .btn-custom:hover { background-color: #1a1a1a; }
    .table th { background-color: #2c2c2c; color: white; }
    .table td { vertical-align: middle; }
    .pagination .page-link {
        background-color: #2c2c2c;
        color: #fff;
        border: none;
    }

    .pagination .page-link:hover {
        background-color: #1a1a1a;
        color: #fff;
    }

    .pagination .page-item.active .page-link {
        background-color: #000; 
        color: #fff;
        border: none;
    }

    .pagination .page-item.disabled .page-link {
        background-color: #5a5a5a;
        color: #ccc;
        border: none;
    }

    .btn-view {
        background-color: #2c2c2c;
        color: #fff;
        border: none;
    }

    .btn-view:hover {
        background-color: #1a1a1a;
        color: #fff;
    }
</style>

<div class="container py-4">
    <h2 class="fw-bold mb-4">Profil és rendelési adatok</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <ul class="nav nav-tabs mb-4" id="userTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">
                @if(Auth::user()?->is_admin)
                    Rendelések
                @else
                    Rendeléseim
                @endif
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="alap-tab" data-bs-toggle="tab" href="#alap" role="tab">Alapadatok</a>
        </li>

        @unless(Auth::user()?->is_admin)
            <li class="nav-item"><a class="nav-link" id="billing-tab" data-bs-toggle="tab" href="#billing" role="tab">Számlázási adatok</a></li>
            <li class="nav-item"><a class="nav-link" id="shipping-tab" data-bs-toggle="tab" href="#shipping" role="tab">Szállítási adatok</a></li>
        @endunless
    </ul>


    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="tab-content" id="userTabContent">
            {{-- Alapadatok --}}
            <div class="tab-pane fade show" id="alap" role="tabpanel">
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
                        <img src="{{ asset('storage/'.$user->profile_image) }}" alt="Profilkép" width="100" class="mb-2 rounded">
                    @endif
                    <input type="file" name="profile_image" class="form-control">
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
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Város</label>
                        <input type="text" name="billing_city" value="{{ old('billing_city', $user->billing_city) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Utca</label>
                        <input type="text" name="billing_street_name" value="{{ old('billing_street_name', $user->billing_street_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Közterület típusa</label>
                        <input type="text" name="billing_street_type" value="{{ old('billing_street_type', $user->billing_street_type) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Házszám</label>
                        <input type="text" name="billing_house_number" value="{{ old('billing_house_number', $user->billing_house_number) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Épület</label>
                        <input type="text" name="billing_building" value="{{ old('billing_building', $user->billing_building) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Emelet</label>
                        <input type="text" name="billing_floor" value="{{ old('billing_floor', $user->billing_floor) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
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
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Város</label>
                        <input type="text" name="shipping_city" value="{{ old('shipping_city', $user->shipping_city) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Utca</label>
                        <input type="text" name="shipping_street_name" value="{{ old('shipping_street_name', $user->shipping_street_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Közterület típusa</label>
                        <input type="text" name="shipping_street_type" value="{{ old('shipping_street_type', $user->shipping_street_type) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Házszám</label>
                        <input type="text" name="shipping_house_number" value="{{ old('shipping_house_number', $user->shipping_house_number) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Épület</label>
                        <input type="text" name="shipping_building" value="{{ old('shipping_building', $user->shipping_building) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Emelet</label>
                        <input type="text" name="shipping_floor" value="{{ old('shipping_floor', $user->shipping_floor) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Ajtó</label>
                        <input type="text" name="shipping_door" value="{{ old('shipping_door', $user->shipping_door) }}" class="form-control">
                    </div>
                </div>
            </div>

            {{-- Rendelések --}}
            <div class="tab-pane fade show active" id="orders" role="tabpanel">
                @if($orders->isEmpty())
                    <div class="alert alert-info mt-3">
                        @if(Auth::user()?->is_admin)
                            Nincsenek rendelések.
                        @else
                            Még nem rendeltél webáruházunkból.
                        @endif
                    </div>
                @else
                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                @if(Auth::user()?->is_admin)
                                    <th>Felhasználó</th>
                                @endif
                                <th>Dátum</th>
                                <th>Összeg</th>
                                <th>Státusz</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    @if(Auth::user()?->is_admin)
                                        <td>{{ $order->user ? $order->user->lastname . ' ' . $order->user->firstname : 'Vendég' }}</td>
                                    @endif
                                    <td>{{ $order->created_at->format('Y.m.d H:i') }}</td>
                                    <td>{{ number_format($order->total, 0, ',', ' ') }} Ft</td>
                                    <td>{{ $order->status ?? 'Feldolgozás alatt' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-custom btn-sm">Mentés</button>
            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary btn-sm">Vissza</a>
        </div>
    </form>
</div>
@endsection
