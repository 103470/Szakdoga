@extends('user')

@section('title', 'Saját fiók')

@section('content')
    <div class="container">
        <h1>Üdv, {{ Auth::user()->firstname }}!</h1>
        <p>Itt láthatod a fiókod adatait, rendeléseidet stb.</p>
    </div>
@endsection
