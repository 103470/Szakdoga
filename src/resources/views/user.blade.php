<!doctype html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Felhasználói felület')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

{{-- FELSŐ NAVIGÁCIÓ --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">Felhasználói felület</a>

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
                        {{-- Ide később jöhet: saját profil, beállítások stb. --}}
                        {{-- <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profilom</a></li> --}}
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

{{-- TARTALOM --}}
<div class="container py-4 flex-grow-1">
    @yield('content')
</div>

{{-- LÁBLÉC --}}
<footer class="bg-dark text-white text-center py-3 mt-auto">
    &copy; {{ date('Y') }} - Felhasználói felület
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
