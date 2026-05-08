@extends('layouts.app')

@section('content')

<div class="container mt-5">
<br>

    <h2 class="text-center">📅 Shifts</h2>

    {{-- ADMIN ONLY --}}
    @if(auth()->user()->role == 'admin')

        <form method="POST" action="/shifts" class="mb-4">
            @csrf

            <input type="date" name="date" class="form-control mb-2">

            <input type="time" name="time" class="form-control mb-2">

            <input type="number" name="required_users" class="form-control mb-2">

            <button class="btn btn-primary w-100">Add Shift</button>
        </form>

    @endif

    {{-- SHIFTS LIST --}}
    @foreach($shifts as $shift)

        <div class="card p-3 mb-2">

            <h5>{{ $shift->date }} - {{ $shift->time }}</h5>

            <p>Joined: {{ $shift->users->count() }}/{{ $shift->required_users }}</p>

            {{-- USER ONLY JOIN --}}
            @if(auth()->user()->role != 'admin')

                @if(!$shift->users->contains(auth()->id()))
                    <a href="/shifts/{{ $shift->id }}/join" class="btn btn-success btn-sm">
                        Join
                    </a>
                @else
                    <span class="text-success">Joined ✔</span>
                @endif

            @endif

        </div>

    @endforeach

</div>

@endsection