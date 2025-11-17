@extends('admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h4 mb-4">Admin vezérlőpult</h1>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Regisztrált márkák</div>
                <div class="value">{{ isset($brands) ? $brands->count() : 0 }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Modellek</div>
                <div class="value">{{ isset($models) ? $models->count() : 0 }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Kategóriák</div>
                <div class="value">{{ isset($categories) ? $categories->count() : 0 }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
