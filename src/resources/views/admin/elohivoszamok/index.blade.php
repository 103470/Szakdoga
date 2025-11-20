@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Előhívószámok</h1>

    <a href="{{ route('admin.elohivoszamok.create') }}" class="btn theme-blue-btn mb-3">
        + Új előhívószám
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($prefixes->isEmpty())
        <div class="alert alert-info">Nincs még rögzített előhívószám.</div>
    @else
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Előhívó</th>
                    <th>Ország</th>
                    <th class="text-end">Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prefixes as $prefix)
                    <tr>
                        <td>{{ $prefix->prefix }}</td>
                        <td>{{ $prefix->country }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.elohivoszamok.edit', $prefix->id) }}"
                               class="btn btn-sm btn-warning">
                                Szerkesztés
                            </a>

                            <form action="{{ route('admin.elohivoszamok.destroy', $prefix->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Biztosan törlöd?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Törlés</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
