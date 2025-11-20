@extends('admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-4">Admin vezérlőpult</h1>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Összes Márka</div>
                <div class="value">{{ $allbrands }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Termékek</div>
                <div class="value">{{ $products->count() }}</div>
            </div>
        </div>
        <div class="col-md-4 mt-4">
            <div class="stat-card">
                <div class="label">Rendelések</div>
                <div class="value">{{ $orders->count() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
