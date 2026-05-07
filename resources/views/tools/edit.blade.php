@extends('layouts.app')

@section('content')

<h2>Edit Tool</h2>

<form method="POST" action="/tools/{{ $tool->id }}">
    @csrf
    @method('PUT')

    <input name="name" value="{{ $tool->name }}" class="form-control mb-2">

    <input name="type" value="{{ $tool->type }}" class="form-control mb-2">

    <select name="status" class="form-control mb-2">
        <option value="available" {{ $tool->status == 'available' ? 'selected' : '' }}>Available</option>
        <option value="checked_out" {{ $tool->status == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
        <option value="in_repair" {{ $tool->status == 'in_repair' ? 'selected' : '' }}>In Repair</option>
    </select>

    <button class="btn btn-primary">Update</button>
</form>

@endsection