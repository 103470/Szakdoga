<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kezdőlap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{asset ('assets/css/style.css') }}" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg theme-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><h2 class="text-light">B+M Autóalkatrész</h2></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div>
        <form class="d-flex" role="search">
            <div class="input-group">
                <input class="form-control" style="width:350px" type="search" placeholder="Kezdj el gépelni" aria-label="Search"/>
                <button class="btn btn-light text-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
      </form>
    </div>
    <div class="d-flex align-items-center gap-2 mx-2">
        <a href="#" class="text-decoration-none text-light">Légy te is eladó</a>

        <a href="#" class="btn theme-orange-btn btn-sm text-light">
            <i class="fa-solid fa-user"></i> Bejelentkezés
        </a>

        <div class="dropdown">
            <a href="#cart-dropdown" class="btn theme-green-btn btn-sm text-light cart-toggle" data-bs-toggle="dropdown">
                <i class="fa-solid fa-cart-shopping"></i>
                Kosár
                <span class="badge bg-warning text-dark cart-count">0</span>
            </a>

            <div class="dropdown-menu dropdown-menu-end p-0" id="cart-dropdown" style="min-width:300px;"></div>
        </div>
    </div>  
  </div>
</nav>

<!-- Kategória Nav -->

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
      <ul class="navbar-nav">
        <li>
            <a class="nav-link active" href="#">Autótípus kereső</a>
        </li>
        <!-- Termékcsoport dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link active" href="#" id="termekcsoportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Termékcsoport<i class="fa-solid fa-bars"></i>
          </a>
          <ul class="dropdown-menu" aria-labelledby="termekcsoportDropdown">
            <li><a class="dropdown-item" href="/termekcsoport/akkumulatorok">Akkumulátorok</a></li>
            <li><a class="dropdown-item" href="/termekcsoport/olajszurok">Olajszűrők</a></li>
            <li><a class="dropdown-item" href="/termekcsoport/futomu">Futómű alkatrészek</a></li>
            <li><a class="dropdown-item" href="/termekcsoport/fenyszoro">Fényszórók</a></li>
          </ul>
        </li>
        <li>
            <a class="nav-link active" href="#">Olaj kereső</a> 
        </li>
        <li>
            <a class="nav-link active" href="#">Akkumulátor</a>
        </li>
        
        
      </ul>
    </div>
  </div>
</nav>

