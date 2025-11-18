@extends('admin')

@section('content')
<div class="container">
    <h1>Típusok</h1> 
    <a href="{{ route('admin.markak.tipusok.create') }}" class="btn theme-blue-btn mb-3">+ Új típus</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th class="w-25">Márka</th>
                <th class="w-50">Típus neve</th>
                <th class="w-25 text-end">Műveletek</th> 
            </tr>
        </thead>
        <tbody>
            @forelse($types as $type)
            <tr>
                <td>{{ $type->brand->name ?? '—' }}</td>
                <td>{{ $type->name }}</td>

                <td class="text-end"> 
                    <a href="{{ route('admin.markak.tipusok.edit', $type->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>

                    <form action="{{ route('admin.markak.tipusok.destroy', $type->id) }}" 
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Biztos törölni akarod?')">
                            Törlés
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Nincs adat</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
