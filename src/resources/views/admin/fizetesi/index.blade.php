@extends('admin')

@section('content')
<div class="container mt-4">
    <h1>Fizetési opciók</h1>

    <a href="{{ route('admin.fizetesi.create') }}" class="btn theme-blue-btn mb-3">+ Új fizetési opció</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th>Név</th>
                <th>Fee</th>
                <th>Leírás</th>
                <th>Típus</th>
                <th>Állapot</th>
                <th class="text-end">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($options as $option)
            <tr>
                <td>{{ $option->name }}</td>
                <td>{{ $option->fee }}</td>
                <td>{{ $option->description }}</td>
                <td>{{ strtoupper($option->type) }}</td>
                <td>
                    @if($option->is_active)
                        <span class="badge bg-success">Aktív</span>
                    @else
                        <span class="badge bg-danger">Inaktív</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.fizetesi.edit', $option->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.fizetesi.destroy', $option->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos törölni akarod?')">Törlés</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Nincs adat</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $options->links() }}
    </div>
</div>
@endsection
