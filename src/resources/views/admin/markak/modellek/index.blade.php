@extends('admin')

@section('content')
<div class="container">
    <h1>Modellek</h1> 
    <a href="{{ route('admin.markak.modellek.create') }}" class="btn theme-blue-btn mb-3">+ Új modell</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th class="w-20">Név</th>
                <th class="w-15">Típus</th>
                <th class="w-10">Frame</th>
                <th class="w-10">Body Type</th>
                <th class="w-10">ccm</th>
                <th class="w-10">kW/LE</th>
                <th class="w-10">Évjárat</th>
                <th class="w-10">Motor típus</th>
                <th class="text-end w-15">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($models as $model)
            <tr>
                <td>{{ $model->full_name }}</td>
                <td>{{ $model->type->name ?? '-' }}</td>
                <td>{{ $model->frame ?? '-' }}</td>
                <td>{{ $model->body_type ?? '-' }}</td>
                <td>{{ $model->ccm ? $model->ccm . ' ccm' : '-' }}</td>
                <td>{{ $model->kwLeFormatted ?? '-' }}</td>
                <td>{{ $model->yearRange ?? '-' }}</td>
                <td>{{ $model->engine_type ?? '-' }}</td>
                <td class="text-end"> 
                    <a href="{{ route('admin.markak.modellek.edit', $model->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.markak.modellek.destroy', $model->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos törölni akarod?')">Törlés</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Nincs adat</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $models->links() }}
    </div>
</div>
@endsection
