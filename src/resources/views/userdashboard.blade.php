@extends('user')

@section('title', 'Profil szerkesztése')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Profil szerkesztése</h2>

    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="card p-4 shadow-sm border-0">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="firstname" class="form-label">Keresztnév</label>
                <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname) }}" required>
            </div>
            <div class="col-md-6">
                <label for="lastname" class="form-label">Vezetéknév</label>
                <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $user->lastname) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email cím</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="profile_image" class="form-label">Profilkép (opcionális)</label><br>
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('default-avatar.png') }}" alt="Profil" class="rounded mb-2" width="80" height="80">
            <input type="file" name="profile_image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Mentés</button>
        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Vissza</a>
    </form>
</div>
@endsection
