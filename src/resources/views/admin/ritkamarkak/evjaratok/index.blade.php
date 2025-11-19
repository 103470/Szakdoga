@extends('admin')

@section('content')
<div class="container">
    <h1>Ritka márkák évjáratai</h1>
    <a href="{{ route('admin.ritkamarkak.evjaratok.create') }}" class="btn theme-blue-btn mb-3">+ Új évjárat</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th>Típus</th>
                <th>Évjárat neve</th>
                <th>Frame</th>
                <th>Body Type</th>
                <th>Időszak</th>
                <th class="text-end">Műveletek</th>
            </tr>
        </thead>
        <tbody>
        @forelse($vintages as $vintage)
            <tr>
                <td>{{ $vintage->type->name ?? '-' }}</td>
                <td>{{ $vintage->name }}</td>
                <td>{{ $vintage->frame }}</td>
                <td>{{ $vintage->body_type }}</td>
                <td>{{ $vintage->vintageRange }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.ritkamarkak.evjaratok.edit', $vintage->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.ritkamarkak.evjaratok.destroy', $vintage->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos törölni akarod?')">Törlés</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Nincs adat</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $vintages->links() }}
</div>
@endsection
