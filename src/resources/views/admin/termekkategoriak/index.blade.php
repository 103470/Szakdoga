@extends('admin')

@section('content')
<div class="container">
    <h1>Termékkategóriák</h1>
    <a href="{{ route('admin.termekkategoriak.create') }}" class="btn theme-blue-btn mb-3">+ Új termékkategória</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th>Alkategória</th>
                <th>Név</th>
                <th class="text-end w-20">Műveletek</th>
            </tr>
        </thead>
        <tbody>
        @forelse($productCategories as $productCategory)
        <tr>
            <td>{{ $productCategory->subcategory->name ?? 'N/A' }}</td>
            <td>{{ $productCategory->name }}</td>
            <td class="text-end">
                <a href="{{ route('admin.termekkategoriak.edit', $productCategory->slug) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                <form action="{{ route('admin.termekkategoriak.destroy', $productCategory->slug) }}" method="POST" class="d-inline">
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

    <div class="mt-3">
        {{ $productCategories->links() }}
    </div>
</div>
@endsection
