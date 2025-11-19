@extends('admin')

@section('content')
<div class="container">
    <h1>Kategóriák</h1> 
    <a href="{{ route('admin.kategoriak.create') }}" class="btn theme-blue-btn mb-3">+ Új kategória</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th class="w-60">Név</th>
                <th class="w-20">Kép</th>
                <th class="text-end w-20">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>
                    @if($category->icon)
                        <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" style="height:40px;">
                    @else
                        -
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('admin.kategoriak.edit', $category->slug) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.kategoriak.destroy', $category->slug) }}" method="POST" class="d-inline">
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
        {{ $categories->links() }}
    </div>
</div>
@endsection
