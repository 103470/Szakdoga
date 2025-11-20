<!doctype html>
<html lang="hu">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Admin')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{asset ('assets/css/style.css') }}" rel="stylesheet">

    <style>
        :root {
            --background: #f3f3f3;
            --highlight: #2c2c2c;
            --hover: #1a1a1a;
            --text: #333;
            --link: #555;
        }

        body {
            background: var(--background);
            color: var(--text);
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* Navbar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            height: 64px;
            padding: 0 1rem;
        }

        .topbar .brand {
            font-weight: 700;
            color: var(--highlight);
        }

        /* Sidebar */
        .sidebar {
            background: var(--highlight);
            color: #fff;
            min-height: 100vh;
            padding-top: 16px;
            width: 260px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            padding: .65rem 1rem;
            border-radius: 8px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--hover);
        }

        .sidebar .nav-link .bi {
            margin-right: .8rem;
        }

        .sidebar a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Main content */
        .content {
            padding: 1.5rem;
            flex: 1;
        }

        /* Cards */
        .stat-card {
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1rem;
        }

        .stat-card .label {
            color: #777;
            font-size: .85rem;
        }

        .stat-card .value {
            font-weight: bold;
            font-size: 1.3rem;
        }

        a {
            color: var(--link);
        }

        a:hover {
            color: var(--hover);
        }

        /* Responsive sidebar */
        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                left: -260px;
                transition: left .25s;
                z-index: 1030;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                padding-top: 80px;
            }
        }
    </style>

    @stack('head')
</head>

<body>
    <!-- Navbar -->
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary btn-sm d-lg-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="brand">Admin Panel</div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <a class="d-flex align-items-center text-dark text-decoration-none" href="#" id="userMenu" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('default-avatar.png') }}" alt="Profil" width="36" height="36" class="rounded-circle me-2">
                    <strong>{{ Auth::user()->firstname ?? 'Admin' }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item">Kijelentkezés</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar p-3" id="sidebarMenu">
            <nav class="nav flex-column">

                <!-- Dinamikus Menü Laravel-ből -->
                @php
                $menus = [
                    ['name' => 'Márkák', 'icon' => 'bi-tag-fill', 'items' => $sidebarBrands ?? []],
                    ['name' => 'Típusok', 'icon' => 'bi-gear-fill', 'items' => $sidebarTypes ?? []],
                    ['name' => 'Évjáratok', 'icon' => 'bi-calendar-event', 'items' => $sidebarYears ?? []],
                    ['name' => 'Modellek', 'icon' => 'bi-car-front-fill', 'items' => $sidebarModels ?? []],
                    ['name' => 'Kategóriák', 'icon' => 'bi-list-ul', 'items' => $sidebarCategories ?? []],
                    ['name' => 'Alkategóriák', 'icon' => 'bi-tags-fill', 'items' => $sidebarSubcategories ?? []],
                    ['name' => 'Termékkategóriák', 'icon' => 'bi-box-seam', 'items' => $sidebarProductCategories ?? []],
                ];

                @endphp

                @foreach ($menus as $menu)
                <div class="nav-item mb-1">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#menu{{ Str::slug($menu['name']) }}">
                        <span><i class="bi {{ $menu['icon'] }}"></i> {{ $menu['name'] }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse" id="menu{{ Str::slug($menu['name']) }}">
                        <ul class="nav flex-column ms-3">

                            @if ($menu['name'] === 'Márkák')
                            <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuPopularBrands">
                                    <span>Népszerű márkák</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>
                                <div class="collapse" id="subMenuPopularBrands">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.markak.index') }}" class="nav-link">Összes népszerű márka</a></li>
                                        <li><a href="{{ route('admin.markak.create') }}" class="nav-link">+ Új népszerű márka</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuRareBrands">
                                    <span>Ritka márkák</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>
                                <div class="collapse" id="subMenuRareBrands">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.ritkamarkak.index') }}" class="nav-link">Összes ritka márka</a></li>
                                        <li><a href="{{ route('admin.ritkamarkak.create') }}" class="nav-link">+ Új ritka márka</a></li>
                                    </ul>
                                </div>
                            </li>

                            <hr class="text-white-50 my-2">
                            @endif

                            @if ($menu['name'] === 'Típusok')
                            <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuPopularTypes">
                                    <span>Népszerű típusok</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>
                                <div class="collapse" id="subMenuPopularTypes">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.markak.tipusok.index') }}" class="nav-link">Összes népszerű típus</a></li>
                                        <li><a href="{{ route('admin.markak.tipusok.create') }}" class="nav-link">+ Új népszerű típus</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuRareTypes">
                                    <span>Ritka típusok</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>
                                <div class="collapse" id="subMenuRareTypes">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.ritkamarkak.tipusok.index') }}" class="nav-link">Összes ritka típus</a></li>
                                        <li><a href="{{ route('admin.ritkamarkak.tipusok.create') }}" class="nav-link">+ Új ritka típus</a></li>
                                    </ul>
                                </div>
                            </li>

                            <hr class="text-white-50 my-2">
                            @endif

                            @if ($menu['name'] === 'Évjáratok')
                                <li class="nav-item">
                                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuPopularYears">
                                        <span>Népszerű évjáratok</span>
                                        <i class="bi bi-chevron-down small"></i>
                                    </a>
                                    <div class="collapse" id="subMenuPopularYears">
                                        <ul class="nav flex-column ms-3">
                                            <li><a href="{{ route('admin.markak.evjaratok.index') }}" class="nav-link">Összes népszerű évjárat</a></li>
                                            <li><a href="{{ route('admin.markak.evjaratok.create') }}" class="nav-link">+ Új népszerű évjárat</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuRareYears">
                                        <span>Ritka évjáratok</span>
                                        <i class="bi bi-chevron-down small"></i>
                                    </a>
                                    <div class="collapse" id="subMenuRareYears">
                                        <ul class="nav flex-column ms-3">
                                            <li><a href="{{ route('admin.ritkamarkak.evjaratok.index') }}" class="nav-link">Összes ritka évjárat</a></li>
                                            <li><a href="{{ route('admin.ritkamarkak.evjaratok.create') }}" class="nav-link">+ Új ritka évjárat</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <hr class="text-white-50 my-2">
                            @endif

                            @if ($menu['name'] === 'Modellek')
                                <li class="nav-item">
                                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuPopularModels">
                                        <span>Népszerű modellek</span>
                                        <i class="bi bi-chevron-down small"></i>
                                    </a>
                                    <div class="collapse" id="subMenuPopularModels">
                                        <ul class="nav flex-column ms-3">
                                            <li><a href="{{ route('admin.markak.modellek.index') }}" class="nav-link">Összes népszerű modell</a></li>
                                            <li><a href="{{ route('admin.markak.modellek.create') }}" class="nav-link">+ Új népszerű modell</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#subMenuRareModels">
                                        <span>Ritka modellek</span>
                                        <i class="bi bi-chevron-down small"></i>
                                    </a>
                                    <div class="collapse" id="subMenuRareModels">
                                        <ul class="nav flex-column ms-3">
                                            <li><a href="{{ route('admin.ritkamarkak.modellek.index') }}" class="nav-link">Összes ritka modell</a></li>
                                            <li><a href="{{ route('admin.ritkamarkak.modellek.create') }}" class="nav-link">+ Új ritka modell</a></li>
                                        </ul>
                                    </div>
                                </li>

                                <hr class="text-white-50 my-2">
                            @endif

                            @if ($menu['name'] === 'Kategóriák')
                            <li class="nav-item">
                                <div class="collapse show" id="subMenuCategories">
                                    <ul class="nav flex-column ms-3">
                                        <li>
                                            <a href="{{ route('admin.kategoriak.index') }}" class="nav-link">Összes kategória</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.kategoriak.create') }}" class="nav-link">+ Új kategória</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endif

                            @if ($menu['name'] === 'Alkategóriák')
                            <li class="nav-item">
                                <div class="collapse show" id="subMenuSubcategories">
                                    <ul class="nav flex-column ms-3">
                                        <li>
                                            <a href="{{ route('admin.alkategoriak.index') }}" class="nav-link">Összes alkategória</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.alkategoriak.create') }}" class="nav-link">+ Új alkategória</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endif

                            @if ($menu['name'] === 'Termékkategóriák')
                                <li class="nav-item">
                                    <div class="collapse show" id="subMenuProductCategories">
                                        <ul class="nav flex-column ms-3">
                                            <li>
                                                <a href="{{ route('admin.termekkategoriak.index') }}" class="nav-link">Összes termékkategória</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.termekkategoriak.create') }}" class="nav-link">+ Új termékkategória</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                @endforeach


                <div class="nav-item mb-1 mt-3">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#menuUsers">
                        <span><i class="bi bi-people-fill"></i> Felhasználók</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse" id="menuUsers">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link">📋 Összes felhasználó</a>
                            </li>
                        </ul>
                    </div>
                </div>

           <div class="mt-4">
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" 
                        data-bs-toggle="collapse" 
                        href="#subMenuProducts">
                            <span><i class="bi bi-box-seam me-2"></i> Termékek</span>
                            <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div class="collapse" id="subMenuProducts">
                        <ul class="nav flex-column ms-3">
                            <li>
                                <a href="{{ route('admin.termekek.index') }}" class="nav-link">Összes termék</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.termekek.create') }}" class="nav-link">+ Új termék</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item mt-2">
                    <a class="nav-link d-flex justify-content-between align-items-center" 
                        data-bs-toggle="collapse" 
                        href="#subMenuOther">
                            <span><i class="bi bi-gear me-2"></i> Egyéb</span>
                            <i class="bi bi-chevron-down small"></i>
                    </a>

                    <div class="collapse" id="subMenuOther">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item mt-1">
                                <a class="nav-link d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#oemMenu">
                                    <span>OEM számok</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>

                                <div class="collapse" id="oemMenu">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.oemszamok.index') }}" class="nav-link">Összes</a></li>
                                        <li><a href="{{ route('admin.oemszamok.create') }}" class="nav-link">+ Új hozzáadása</a></li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item mt-1">
                                <a class="nav-link d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#kapcsolasMenu">
                                    <span>Termékkapcsolás</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>

                                <div class="collapse" id="kapcsolasMenu">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.termekkapcsolas.index') }}" class="nav-link">Összes</a></li>
                                        <li><a href="{{ route('admin.termekkapcsolas.create') }}" class="nav-link">+ Új hozzáadása</a></li>
                                    </ul>
                                </div>
                            </li>

                            {{-- Előhívószámok --}}
                            <li class="nav-item mt-1">
                                <a class="nav-link d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#elohivoszamMenu">
                                    <span>Előhívószámok</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>

                                <div class="collapse" id="elohivoszamMenu">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.elohivoszamok.index') }}" class="nav-link">Összes</a></li>
                                        <li><a href="{{ route('admin.elohivoszamok.create') }}" class="nav-link">+ Új hozzáadása</a></li>
                                    </ul>
                                </div>
                            </li>

                            {{-- Szállítási opciók --}}
                            <li class="nav-item mt-1">
                                <a class="nav-link d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#szallitasMenu">
                                    <span>Szállítási opciók</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>

                                <div class="collapse" id="szallitasMenu">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.szallitasi.index') }}" class="nav-link">Összes</a></li>
                                        <li><a href="{{ route('admin.szallitasi.create') }}" class="nav-link">+ Új hozzáadása</a></li>
                                    </ul>
                                </div>
                            </li>

                            {{-- Fizetési opciók --}}
                            <li class="nav-item mt-1">
                                <a class="nav-link d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#fizetesiMenu">
                                    <span>Fizetési opciók</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>

                                <div class="collapse" id="fizetesiMenu">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.fizetesi.index') }}" class="nav-link">Összes</a></li>
                                        <li><a href="{{ route('admin.fizetesi.create') }}" class="nav-link">+ Új hozzáadása</a></li>
                                    </ul>
                                </div>
                            </li>

                            {{-- Üzemanyag típusok --}}
                            <li class="nav-item mt-1">
                                <a class="nav-link d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse"
                                href="#uzemanyagMenu">
                                    <span>Üzemanyag típusok</span>
                                    <i class="bi bi-chevron-down small"></i>
                                </a>

                                <div class="collapse" id="uzemanyagMenu">
                                    <ul class="nav flex-column ms-3">
                                        <li><a href="{{ route('admin.uzemanyag.index') }}" class="nav-link">Összes</a></li>
                                        <li><a href="{{ route('admin.uzemanyag.create') }}" class="nav-link">+ Új hozzáadása</a></li>
                                    </ul>
                                </div>
                            </li>

                        </ul>
                    </div>
                </li>

            </div>


            </nav>
        </aside>

        <!-- Tartalom -->
        <main class="content container-fluid">
            @yield('content')
        </main>
    </div>

    <footer class="text-center py-3 mt-3">
        <small class="text-muted">&copy; {{ date('Y') }} Autóalkatrész Webshop</small>
    </footer>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarToggle')?.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebarMenu');
                sidebar.classList.toggle('show');
            });
        
            document.querySelectorAll('.toggle-description').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const td = this.closest('td');
                    td.querySelector('.short-text').classList.toggle('d-none');
                    td.querySelector('.full-text').classList.toggle('d-none');
                    this.textContent = this.textContent === '...tovább' ? 'bezár' : '...tovább';
                });
            });
        });
        
    </script>

    @stack('scripts')
</body>

</html>