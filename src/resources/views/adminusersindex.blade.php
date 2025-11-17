@extends('admin')

@section('title', 'Felhasználók kezelése')

@section('content')
<div class="container">
    <h1 class="mb-4">Felhasználók</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Név</th>
                <th>Email</th>
                <th>Fiók típus</th>
                <th>Admin</th>
                <th>Művelet</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->account_type ?? '-' }}</td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge bg-success">Igen</span>
                        @else
                            <span class="badge bg-secondary">Nem</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Szerkesztés
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Biztosan törölni szeretnéd ezt a felhasználót?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Törlés</button>
                    </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Nincs regisztrált felhasználó</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
