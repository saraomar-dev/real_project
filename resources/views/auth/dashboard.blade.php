@extends('layouts.app')

@section('styles')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f6fa;
}

.dashboard-container{
    padding:40px;
}

.card-box{
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    margin-bottom:20px;
    text-align:center;
}

.card-box h4{
    font-size:20px;
    margin-bottom:10px;
}

.card-box p{
    font-size:28px;
    font-weight:bold;
    color:#0d6efd;
}

.section-title{
    margin-top:40px;
    margin-bottom:20px;
    font-weight:bold;
}

.report-card{
    background:white;
    padding:20px;
    border-radius:12px;
    margin-bottom:15px;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

</style>

@endsection


@section('content')

<div class="container dashboard-container">

@if(auth()->check() && strtolower(trim(auth()->user()->role)) === 'admin')

    {{-- ================= ADMIN DASHBOARD ================= --}}

    <h2 class="mb-4 text-center">👨‍💼 Admin Dashboard</h2>

    <div class="row">

        <div class="col-md-4">
            <div class="card-box">
                <h4>Total Users</h4>
                <p>{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h4>Admins</h4>
                <p>{{ $admins }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h4>Normal Users</h4>
                <p>{{ $users }}</p>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="card-box">
                <h4>Total Tasks</h4>
                <p>{{ $tasksCount }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h4>Done Tasks</h4>
                <p>{{ $doneTasks }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h4>Pending Tasks</h4>
                <p>{{ $pendingTasks }}</p>
            </div>
        </div>

    </div>

    <h3 class="section-title">📋 Recent Activity</h3>

    <div class="card-box">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Action</th>
                    <th>Model</th>
                    <th>User ID</th>
                    <th>Time</th>
                </tr>
            </thead>

            <tbody>

            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->model }}</td>
                    <td>{{ $log->user_id }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach

            </tbody>

        </table>

    </div>

    <h3 class="section-title">🔧 Damage Reports</h3>

    @foreach($damages ?? [] as $d)

        <div class="report-card">

            <p>{{ $d->description }}</p>

            @if($d->image)
                <img src="{{ asset('storage/' . $d->image) }}" width="150" class="rounded">
            @endif

            <br><br>

            @if(!$d->fine)
                <a href="/damage/{{ $d->id }}/fine" class="btn btn-warning btn-sm">
                    Add Fine
                </a>
            @else
                <span class="text-success">✔ Fine Added</span>
            @endif

        </div>

    @endforeach


    <h3 class="section-title">🚨 Incident Reports</h3>

    @foreach($incidents ?? [] as $i)

        <div class="report-card">

            <p>{{ $i->description }}</p>

            @if($i->image)
                <img src="{{ asset('storage/' . $i->image) }}" width="150" class="rounded">
            @endif

            <br><br>

            <span class="badge bg-danger">
                Severity: {{ $i->severity ?? 'N/A' }}
            </span>

        </div>

    @endforeach


@else

    {{-- ================= USER DASHBOARD ================= --}}

    <h2 class="mb-4 text-center">👤 User Dashboard</h2>

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card-box">

                <h4>Your Volunteer Hours</h4>

                <p>
                    {{ auth()->check() ? (auth()->user()->volunteerHours->sum('hours') ?? 0) : 0 }}
                </p>

            </div>

        </div>

    </div>

    <div class="card-box mt-4">

        <h4>📌 Your Tasks / Shifts / Requests</h4>

        <p class="text-muted">Your activities will appear here.</p>

    </div>

@endif

</div>

@endsection