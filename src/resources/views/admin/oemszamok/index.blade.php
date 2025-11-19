@extends('admin')

@section('content')
<div class="container">
    <h1>OEM számok</h1>

    <a href="{{ route('admin.oemszamok.create') }}" class="btn theme-blue-btn mb-3">
        + Új OEM szám
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th>OEM szám</th>
                <th>Termék</th>
                <th class="text-end">Műveletek</th>
            </tr>
        </thead>

        <tbody>
            @forelse($oemNumbers as $oem)
            <tr>
                <td>{{ $oem->oem_number }}</td>

                <td>
                    {{ $oem->product->name ?? 'Nincs termék hozzárendelve' }}
                </td>

                <td class="text-end">
                    <a href="{{ route('admin.oemszamok.edit', $oem->id) }}" 
                       class="btn btn-sm btn-warning">
                        Szerkesztés
                    </a>

                    <form action="{{ route('admin.oemszamok.destroy', $oem->id) }}" 
                          method="POST" 
                          class="d-inline">
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

    <div class="mt-3">
        {{ $oemNumbers->links() }}
    </div>
</div>
@endsection
