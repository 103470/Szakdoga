@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Üzemanyag típusok</h1>

    <a href="{{ route('admin.uzemanyag.create') }}" class="btn theme-blue-btn mb-3">+ Új üzemanyag típus</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th>Név</th>
                <th>Univerzális</th>
                <th class="text-end">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fuels as $fuel)
            <tr>
                <td>{{ $fuel->name }}</td>
                <td>
                    @if($fuel->is_universal)
                        <span class="badge bg-success">Univerzális</span>
                    @else
                        <span class="badge bg-danger">Nem Univerzális</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.uzemanyag.edit', $fuel->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.uzemanyag.destroy', $fuel->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos törölni akarod?')">Törlés</button>
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
