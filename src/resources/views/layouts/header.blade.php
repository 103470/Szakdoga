<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kezdőlap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{asset ('assets/css/style.css') }}" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg theme-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><h2 class="text-light">B+M Webáruház</h2></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div>
        <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
        <button class="btn btn-light text-secondary" type="submit">Search</button>
      </form>
    </div>
    <div class="mx-2">
        <a href="#" class="text-decoration-none text-light">Légy te is eladó</a>
        <a href="#" class="btn theme-orange-btn btn-sm text-light"><i class="fa-solid fa-user"></i>Bejelentkezés</a>
        <a href="#" class="btn theme-green-btn btn-sm text-light"><i class="fa-solid fa-cart-shopping"></i>Kosár</a>
    </div>
  </div>
</nav>
