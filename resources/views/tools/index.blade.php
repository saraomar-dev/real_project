@extends('layouts.app')

@section('content')
<br>
<br>
<h2>Tools List</h2>

{{-- ADMIN ONLY --}}
@if(auth()->check() && strtolower(trim(auth()->user()->role)) === 'admin')

    <a href="/tools/create" class="btn btn-primary mb-3">
        + Add Tool
    </a>

@endif


@foreach($tools as $tool)

    <div class="card mb-2 p-3">

        <h4>{{ $tool->name }}</h4>

        <p>Type: {{ $tool->type }}</p>

        <p>Status: {{ $tool->status }}</p>


        {{-- ADMIN ONLY --}}
        @if(auth()->check() && strtolower(trim(auth()->user()->role)) === 'admin')

            <a href="/tools/{{ $tool->id }}/edit"
               class="btn btn-warning btn-sm">
               Edit
            </a>


            <form method="POST"
                  action="/tools/{{ $tool->id }}"
                  style="display:inline;">

                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm">
                    Delete
                </button>

            </form>

        @endif



        {{-- USER ONLY --}}
        @if(auth()->check() && strtolower(trim(auth()->user()->role)) === 'user')

            <form method="POST"
                  action="/damage"
                  enctype="multipart/form-data"
                  class="mt-3">

                @csrf

                <input type="hidden"
                       name="tool_id"
                       value="{{ $tool->id }}">

                <textarea name="description"
                          placeholder="Describe damage"
                          class="form-control mb-2"></textarea>

                <input type="file"
                       name="image"
                       class="form-control mb-2">

                <button type="submit"
                        class="btn btn-danger btn-sm">

                    Report Damage

                </button>

            </form>



            <form method="POST"
                  action="/incident"
                  enctype="multipart/form-data"
                  class="mt-3">

                @csrf

                <input type="text"
                       name="title"
                       placeholder="Problem title"
                       class="form-control mb-2">

                <textarea name="description"
                          placeholder="Description"
                          class="form-control mb-2"></textarea>

                <select name="severity"
                        class="form-control mb-2">

                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>

                </select>

                <input type="file"
                       name="image"
                       class="form-control mb-2">

                <button class="btn btn-danger">
                    Report Incident
                </button>

            </form>

        @endif

    </div>

@endforeach

@endsection