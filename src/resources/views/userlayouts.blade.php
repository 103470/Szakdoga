<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Felhasználói felület')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-tabs .nav-link {
            background-color: #7a7a7aff  ;
            /* fehér háttér a nem aktív taboknak */
            color: #000;
            /* fekete szöveg */
            border: 1px solid #F3F3F3;
            margin-right: 2px;
        }

        .nav-tabs .nav-link.active {
            background-color: #000;
            /* fekete háttér az aktív tabnak */
            color: #f3f3f3 ;
            /* fehér szöveg */
        }

        .nav-tabs .nav-link:hover:not(.active) {
            background-color: #f3f3f3 ;
            color: #000;
        }

        .navbar {
            background-color: #2c2c2c  !important;
        }

        .navbar-brand {
            color: #f3f3f3 !important; /* fehér szín a Webshop felirathoz */
        }

        .nav-link,
        .navbar-text {
            color: #f3f3f3  !important;
        }

        .nav-link.active {
            color: #f3f3f3  !important;
            font-weight: bold;
        }
        .nav-link:hover {
            color: #f3f3f3  !important;
        }
    </style>
</head>

<body>
    {{-- FELSŐ NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('user.dashboard') }}">Webshop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">Profil</a>
                    </li>
                    @if(Auth::user()?->is_admin)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin') }}">Admin</a>
                    </li>
                    @endif
                </ul>

                <span class="navbar-text me-3">
                    Bejelentkezve: <strong>{{ Auth::user()?->firstname ?? '' }}</strong>
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Kijelentkezés</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- TARTALOM --}}
    <main class="container">
        @yield('content')
    </main>

    {{-- LÁBLÉC --}}
    <footer class="footer text-center mt-5">
        <div class="container">
            <small>
                &copy; {{ date('Y') }} Webshop |
                <a href="{{ route('aszf') }}">ÁSZF</a> |
                <a href="{{ route('adatvedelem') }}">Adatkezelés</a>
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>