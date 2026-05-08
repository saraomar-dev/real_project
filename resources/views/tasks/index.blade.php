

@extends('layouts.app')


<h1>{{ auth()->user()->role }}</h1>
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f5f6fa;
    }

    .task-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .task-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 5px solid #0d6efd;
    }
</style>
@endsection


@section('content')

<div class="container mt-5">

    <h2 class="text-center mb-4">📋 Tasks Management</h2>

    {{-- FORM (Admin only) --}}
    @if(auth()->user()->role == 'admin')

    <div class="task-card mb-4">

        <form method="POST" action="/tasks">
            @csrf

            <div class="mb-3">
                <label>Task Title</label>
                <input type="text" name="title" class="form-control">
            </div>

            <div class="mb-3">
                <label>Difficulty</label>
                <select name="difficulty" class="form-select">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>

            <button class="btn btn-primary w-100">➕ Add Task</button>

        </form>

    </div>

    @endif


    {{-- LIST --}}
    @foreach($tasks as $task)

        <div class="task-item">

            <h5>{{ $task->title }}</h5>

            <p>
                Difficulty:
                {{ $task->difficulty }}
            </p>

            <p>
                Status: {{ $task->status }}
            </p>

            @if($task->status != 'done')
                <a href="/tasks/{{ $task->id }}/done" class="btn btn-success btn-sm">
                    Mark as Done
                </a>
            @else
                <span class="text-success">✔ Done</span>
            @endif

        </div>

    @endforeach

</div>
<h1>{{auth()->user()->role}}</h1>
@endsection