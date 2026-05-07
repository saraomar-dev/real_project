@extends('layouts.app')

@section('content')

<div class="container mt-4">
<br>
<br>
    <h2>Reservations</h2>

    @foreach($reservations as $res)

        <div class="card p-3 mb-2">

            <h5>{{ $res->tool->name }}</h5>

            @if(auth()->user()->role == 'admin')
                <p>User: {{ $res->user_name }}</p>
            @endif

            <p>Date: {{ $res->reservation_date }}</p>

        </div>

    @endforeach

</div>

@endsection