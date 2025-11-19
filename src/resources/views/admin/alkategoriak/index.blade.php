@extends('admin')

@section('content')
<div class="container">
    <h1>Alkategóriák</h1> 
    <a href="{{ route('admin.alkategoriak.create') }}" class="btn theme-blue-btn mb-3">+ Új alkategória</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th class="w-30">Kategória</th>
                <th class="w-25">Név</th>
                <th class="w-25">Üzemanyag</th>
                <th class="text-end w-20">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subcategories as $subcategory)
            <tr>
                <td>{{ $subcategory->category->name ?? '-' }}</td>
                <td>{{ $subcategory->name }}</td>
                <td>{{ $subcategory->fuelType->name ?? '-' }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.alkategoriak.edit', $subcategory->slug) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.alkategoriak.destroy', $subcategory->slug) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos törölni akarod?')">Törlés</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Nincs adat</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $subcategories->links() }}
    </div>
</div>
@endsection
