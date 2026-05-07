@extends('layouts.app')

@section('content')
<br>
<br>

<h2>Add Tool</h2>

<form method="POST" action="/tools">
    @csrf

    <input name="name" placeholder="Name" class="form-control mb-2">

    <input name="type" placeholder="Type" class="form-control mb-2">

    <select name="status" class="form-control mb-2">
        <option value="available">Available</option>
        <option value="checked_out">Checked Out</option>
        <option value="in_repair">In Repair</option>
    </select>

    <button class="btn btn-success">Save</button>
</form>

@endsection