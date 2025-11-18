@extends('admin')

@section('content')
<div class="container">
    <h1>Ritka márkák típusai</h1>

    <a href="{{ route('admin.ritkamarkak.tipusok.create') }}" class="btn theme-blue-btn mb-3">+ Új típus</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th class="w-50">Márka</th>
                <th>Típus neve</th>
                <th class="text-end w-25">Műveletek</th>
            </tr>
        </thead>
        <tbody>
        @forelse($types as $type)
            <tr>
                <td>{{ $type->rareBrand->name ?? '-' }}</td>
                <td>{{ $type->name }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.ritkamarkak.tipusok.edit', $type->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>

                    <form action="{{ route('admin.ritkamarkak.tipusok.destroy', $type->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos törlöd?')">Törlés</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">Nincs típus</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
