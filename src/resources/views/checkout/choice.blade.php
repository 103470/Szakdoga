@extends('layouts.main')

@section('content')
<div class="body-container my-5">

    <h3 class="text-center mb-5  ps-3">
        Válassz a folytatáshoz
    </h3>

    <div class="row justify-content-center">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h4 class="fw-semibold mb-4">Regisztráció nélkül folytatom</h4>
                    <i class="fa-solid fa-user-secret fa-4x text-secondary mb-4"></i>
                    <p class="text-muted mb-4" style="max-width: 280px;">
                        Vásárolj gyorsan, anélkül hogy regisztrálnál – csak add meg a szállítási és számlázási adataidat.
                    </p>
                    <a href="{{ route('checkout.details') }}" class="btn btn-primary btn-lg px-5 fw-bold">
                        Vendégként vásárolok
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h4 class="fw-semibold mb-4">Már van fiókja?</h4>
                    <i class="fa-solid fa-user fa-4x text-primary mb-4"></i>
                    <a href="#" class="btn btn-outline-primary btn-lg px-5 fw-bold mb-4">
                        Bejelentkezés
                    </a>

                    <p class="text-muted mb-3" style="max-width: 280px;">
                        Ha még nem rendelkezel felhasználói fiókkal, regisztrálj most és élvezd a gyorsabb vásárlást.
                    </p>
                    <a href="#" class="btn btn-success btn-lg px-5 fw-bold">
                        Regisztráció
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
