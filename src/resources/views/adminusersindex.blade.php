@extends('admin')

@section('title','Felhasználók')

@section('content')
<h1>Felhasználók</h1>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Név</th>
      <th>Email</th>
      <th>Telefon</th>
      <th>Számlázási város</th>
      <th>Szállítási város</th>
      <th>Profilkép</th>
      <th>Műveletek</th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
      <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->lastname }} {{ $user->firstname }}</td>
        <td>{{ $user->email }}</td>
        <td>+{{ $user->phone_country_code }} {{ $user->phone_number }}</td>
        <td>{{ $user->billing_city }}</td>
        <td>{{ $user->shipping_city }}</td>
        <td>
          @if($user->profile_image)
            <img src="{{ asset('storage/'.$user->profile_image) }}" width="40">
          @else
            <span class="text-muted">Nincs</span>
          @endif
        </td>
        <td>
          <a href="{{ route('admin.users.edit',$user) }}" class="btn btn-sm btn-primary">Szerkesztés</a>
          <form method="POST" action="{{ route('admin.users.destroy',$user) }}" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törlöd?')">Törlés</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{{ $users->links() }}
@endsection
