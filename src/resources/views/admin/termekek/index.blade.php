@extends('admin')

@section('content')
<div class="container">
    <h1>Termékek</h1>

    <a href="{{ route('admin.termekek.create') }}" class="btn theme-blue-btn mb-3">+ Új termék</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped w-100">
        <thead>
            <tr>
                <th>Alkategória</th>
                <th>Termékkategória</th>
                <th>Név</th>
                <th>Kép</th>
                <th>Cikkszám</th>
                <th>Gyártó</th>
                <th>Leírás</th>
                <th>Ár</th>
                <th>Valuta</th>
                <th>Raktáron</th>
                <th>Állapot</th>
                <th class="text-end">Műveletek</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->subcategory->name ?? 'N/A' }}</td>
                <td>{{ $product->productCategory->name ?? 'N/A' }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="height:40px;">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $product->article_number }}</td>
                <td>{{ $product->manufacturer }}</td>
                <td>
                    @php
                        $short = Str::limit($product->description, 50);
                    @endphp

                    <span class="short-text">{{ $short }}</span>
                    @if(strlen($product->description) > 50)
                        <span class="full-text d-none">{{ $product->description }}</span>
                        <a href="#" class="toggle-description">...tovább</a>
                    @endif
                </td>
                <td>{{ number_format($product->price, 0, ',', ' ') }}</td>
                <td>{{ $product->currency }}</td>
                <td>{{ $product->stock }} db</td>
                <td>{{ $product->is_active ? 'Igen' : 'Nem' }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.termekek.edit', $product->slug) }}" class="btn btn-sm btn-warning">Szerkesztés</a>
                    <form action="{{ route('admin.termekek.destroy', $product->slug) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos törölni akarod?')">Törlés</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center">Nincs adat</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
