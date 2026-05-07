@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/index_users.css') }}">
@endsection


@section('content')

@if (auth()->user()->role === 'admin')
<div class="add-user-container">
    <a href="{{ route('users.create') }}" class="btn-add-user">
         Add User
    </a>
</div>
@endif
<div class="table-container">
    <table class="table custom-table align-middle mb-0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        @foreach ($users as $user)
        <tbody>
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                 <td>{{ $user->phone }}</td>
                <td><span class="status active">{{ $user->role }}</span></td>
                <td>

                    <a href="{{ route('users.show', $user->id) }}" class="btn-edit">Show</a>
                    @if (auth()->user()->role === 'admin')
                    <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">Edit</a> 
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')

    <button type="submit" class="btn-edit">Delete</button>
  </form>
                    @endif
     @if(auth()->id() !=$user->id)               
    <form method="POST" action="/rate">

        @csrf

        <input type="hidden" name="to_user_id" value="{{ $user->id }}">

        <input type="number" name="rating" min="1" max="5" style="width:70px;">

        <button class="btn btn-primary btn-sm">Rate</button>

    </form>
    @endif

                </td>
            </tr>
        </tbody>
        @endforeach
    </table>
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
@endsection
