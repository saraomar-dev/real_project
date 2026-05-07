@extends('layouts.app')

@section('content')

<div class="container mt-4">
<br>
<br>
    <h2>Reserve Tool</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="/reservations">
        @csrf

        <select name="tool_id" class="form-control mb-2">
            @foreach($tools as $tool)
                <option value="{{ $tool->id }}">
                    {{ $tool->name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="reservation_date" class="form-control mb-2">

        <button class="btn btn-success">Reserve</button>
    </form>

</div>

@endsection