<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin panel</a>

        <div class="d-flex ms-auto">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <img 
                            src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('default-avatar.png') }}" 
                            alt="Profilkép" 
                            class="rounded-circle me-2" 
                            width="32" 
                            height="32">
                        {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        {{-- <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil szerkesztése</a></li> --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item">Kijelentkezés</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>

<div class="container-fluid flex-grow-1">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Irányítópult</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Felhasználók</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
            @yield('content')
        </main>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; {{ date('Y') }}
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
