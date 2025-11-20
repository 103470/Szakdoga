@extends('admin')

@section('content')
<div class="container">
    <h1>Szállítási módok</h1>

    <a href="{{ route('admin.szallitasi.create') }}" class="btn theme-blue-btn mb-3">
        + Új szállítási mód
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Név</th>
                <th>Ár</th>
                <th>Leírás</th>
                <th>Állapot</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($options as $option)
                <tr>
                    <td>{{ $option->name }}</td>
                    <td>{{ number_format($option->price, 0, ',', ' ') }} Ft</td>
                    <td>{{ $option->description ?: '-' }}</td>

                    <td>
                        @if($option->is_active)
                            <span class="badge bg-success">Aktív</span>
                        @else
                            <span class="badge bg-danger">Inaktív</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.szallitasi.edit', $option->id) }}"
                           class="btn btn-sm btn-warning">
                            Szerkesztés
                        </a>

                        <form action="{{ route('admin.szallitasi.destroy', $option->id) }}"
                              method="POST"
                              style="display:inline-block"
                              onsubmit="return confirm('Biztosan törlöd?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                Törlés
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nincs szállítási mód rögzítve.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
