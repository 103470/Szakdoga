@extends('admin')

@section('content')
<div class="container">
    <h1>Járművek (PartVehicle)</h1>

    <a href="{{ route('admin.termekkapcsolas.create') }}" class="btn theme-blue-btn mb-3">
        + Új jármű hozzáadása
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th>OEM szám</th>
                <th>Model forrás</th>
                <th>Model kód</th>
                <th class="text-end">Műveletek</th>
            </tr>
        </thead>

        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $item->oemNumber->oem_number ?? 'N/A' }}</td>
                <td>
                    @if($item->model_source === 'brand')
                        <span class="badge bg-primary">Brand</span>
                    @else
                        <span class="badge bg-secondary">Rare Brand</span>
                    @endif
                </td>
                <td>{{ $item->unique_code }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.termekkapcsolas.edit', $item->id) }}"
                       class="btn btn-sm btn-warning">
                        Szerkesztés
                    </a>

                    <form action="{{ route('admin.termekkapcsolas.destroy', $item->id) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Biztos törölni szeretnéd?')">
                            Törlés
                        </button>
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
        {{ $items->links() }}
    </div>
</div>
@endsection
