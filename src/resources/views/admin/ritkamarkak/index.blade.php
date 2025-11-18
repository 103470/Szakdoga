@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Ritka márkák</h1>
    <a href="{{ route('admin.ritkamarkak.create') }}" class="btn theme-blue-btn mb-3">+ Új ritka márka</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th class="w-75">Név</th>
                <th class="w-25">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rareBrands as $rareBrand)
            <tr>
                <td>{{ $rareBrand->name }}</td>
                <td>
                    <a href="{{ route('admin.ritkamarkak.edit', $rareBrand->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.ritkamarkak.destroy', $rareBrand->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos végleg törölni akarod?')">Törlés</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">Nincs adat</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
