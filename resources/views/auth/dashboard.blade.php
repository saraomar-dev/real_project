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

    {{-- إضافات مفيدة من النسخة التانية --}}
    <div class="card-box shadow-sm border-start border-danger border-4">
        <h4>Pending Issues</h4>
        <p class="text-danger fw-bold">{{ \App\Models\Complaint::where('status', 'pending')->count() }}</p>
    </div>

    <div class="card-box shadow-sm border-start border-warning border-4">
        <h4>Waitlist</h4>
        <p class="text-warning fw-bold">{{ \App\Models\Waitlist::where('status', 'waiting')->count() }}</p>
        <small><a href="{{ route('admin.waitlist') }}">View Details</a></small>
    </div>

    <div class="card-box shadow-sm border-start border-primary border-4">
        <h4>Active Leases</h4>
        <p class="text-primary fw-bold">{{ \App\Models\Lease::where('status', 'active')->count() }}</p>
    </div>

    {{-- LOGS --}}
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

    {{-- COMPLAINTS --}}
    <h3 class="section-title text-danger">⚠️ User Complaints</h3>

    <div class="card-box">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Plot</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach(\App\Models\Complaint::with(['user','plot'])->where('status','pending')->latest()->take(5)->get() as $complaint)
                <tr>
                    <td>{{ $complaint->user->name }}</td>
                    <td>#{{ $complaint->plot->plot_number }}</td>
                    <td>{{ $complaint->title }}</td>
                    <td>Pending</td>
                    <td>
                        <form method="POST" action="{{ route('complaints.resolve', $complaint->id) }}">
                            @csrf
                            <button class="btn btn-success btn-sm">Resolve</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@else

    {{-- ================= USER DASHBOARD ================= --}}

    <h2 class="text-center">👤 User Dashboard</h2>

    <div class="card-box">
        <h4>Your Volunteer Hours</h4>
        <p>{{ auth()->user()->volunteerHours->sum('hours') ?? 0 }}</p>
    </div>

@endif

</div>

@endsection