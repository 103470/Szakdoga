@extends('admin')

@section('content')
<div class="container">
    <h1>Gyakori márkák</h1> 
    <a href="{{ route('admin.markak.create') }}" class="btn theme-blue-btn mb-3">+ Új márka</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th class="w-50">Név</th>
                <th class="w-25">Logo</th>
                <th class="text-end w-25">Műveletek</th> 
            </tr>
        </thead>
        <tbody>
            @forelse($brands as $brand)
            <tr>
                <td>{{ $brand->name ?? '-' }}</td>
                <td>
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" width="50">
                    @endif
                </td>
                <td class="text-end"> 
                    <a href="{{ route('admin.markak.edit', $brand->id) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.markak.destroy', $brand->id) }}" method="POST" class="d-inline">
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
