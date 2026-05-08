@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection


@section('content')

<div class="cards-container">

    <div class="card-box">
        <h4>Total Users</h4>
        <p>{{ $totalUsers }}</p>
    </div>

    <div class="card-box">
        <h4>Admins</h4>
        <p>{{ $admins }}</p>
    </div>

    <div class="card-box">
        <h4>Normal Users</h4>
        <p>{{ $users }}</p>
    </div>
    <div class="card-box shadow-sm border-start border-danger border-4">
    <h4>Pending Issues</h4>
    <p class="text-danger fw-bold">{{ \App\Models\Complaint::where('status', 'pending')->count() }}</p>
    <small>Action required</small>
</div>

    <div class="card-box">
        <h4>Other Roles</h4>
        {{-- <p>{{ $others }}</p> --}}
        <p>not yet</p>
    </div>

    <div class="card-box shadow-sm border-start border-warning border-4">
        <h4>Waitlist</h4>
        <p class="text-warning fw-bold">{{ \App\Models\Waitlist::where('status', 'waiting')->count() }}</p>
        <small><a href="{{ route('admin.waitlist') }}" class="text-decoration-none">View Details →</a></small>
    </div>

    <div class="card-box shadow-sm border-start border-primary border-4">
    <h4>Active Leases</h4>
    <p class="text-primary fw-bold">{{ \App\Models\Lease::where('status', 'active')->count() }}</p>
    <small><a href="{{ route('leases.index') }}" class="text-decoration-none text-primary">Manage Leases →</a></small>
</div>



</div>
<div class="dashboard-actions">
    <a href="{{ route('users.index') }}" class="btn-dashboard">
        👥 Manage Users
    </a>

    <a href="{{ route('admin.waitlist') }}" class="btn-dashboard">
        📋 Open Waitlist
    </a>
    <a href="{{ route('leases.index') }}" class="btn-dashboard" style="background-color: #0d6efd; color: white;">
    📜 View All Leases
</a>


</div>
<h3 class="section-title">Recent Activity</h3>
<div class="table-container">
    <table class="table custom-table align-middle mb-0">
        <thead>
            <tr>
        <th>Action</th>
        <th>Model</th>
        <th>User ID</th>
        <th>Time</th>
        <th>Action</th> 
            </tr>
        </thead>
         @foreach($logs as $log)
        <tr>
            <td>{{ $log->action }}</td>
            <td>{{ $log->model }}</td>
            <td>{{ $log->user_id }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
         @endforeach
      
        
    </table>
</div>
<h3 class="section-title text-danger mt-5">⚠️ User Complaints (Recent)</h3>
<div class="table-container shadow-sm border-top border-danger border-3">
    <table class="table custom-table align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Farmer</th>
                <th>Plot #</th>
                <th>Issue Title</th>
                <th>Status</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse(\App\Models\Complaint::with(['user', 'plot'])->where('status', 'pending')->latest()->take(5)->get() as $complaint)
                <tr>
                    <td class="fw-bold">{{ $complaint->user->name }}</td>
                    <td><span class="badge bg-info">#{{ $complaint->plot->plot_number }}</span></td>
                    <td>
                        <div class="fw-bold text-dark">{{ $complaint->title }}</div>
                        <div class="small text-muted">{{ Str::limit($complaint->description, 50) }}</div>
                    </td>
                    <td><span class="badge bg-light-danger text-danger">Pending</span></td>
                    <td>{{ $complaint->created_at->diffForHumans() }}</td>
                    <td>
    <form action="{{ route('complaints.resolve', $complaint->id) }}" method="POST" onsubmit="return confirm('Are you sure this issue is resolved?')">
        @csrf
        <button type="submit" class="btn btn-sm btn-success shadow-0">
            <i class="bi bi-check-lg"></i> Resolve
        </button>
    </form>
</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Great! No pending complaints.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
@endsection
