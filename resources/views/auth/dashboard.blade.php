@extends('layouts.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background:#f5f6fa; }
    .dashboard-container { padding:40px; }
    .card-box { background:white; border-radius:15px; padding:25px; box-shadow:0 4px 12px rgba(0,0,0,0.08); margin-bottom:20px; text-align:center; }
    .card-box h4 { font-size:18px; margin-bottom:10px; font-weight: 600; }
    .card-box p { font-size:28px; font-weight:bold; color:#0d6efd; margin-bottom: 0; }
    .section-title { margin-top:40px; margin-bottom:20px; font-weight:bold; border-bottom: 2px solid #dee2e6; padding-bottom: 10px; }
    .report-card { background:white; padding:20px; border-radius:12px; margin-bottom:15px; box-shadow:0 3px 10px rgba(0,0,0,0.05); border-left: 5px solid #dc3545; }
    .btn-dashboard { display: inline-block; padding: 10px 20px; background: #fff; border: 1px solid #0d6efd; color: #0d6efd; border-radius: 8px; text-decoration: none; margin-right: 10px; transition: 0.3s; }
    .btn-dashboard:hover { background: #0d6efd; color: #fff; }
</style>
@endsection

@section('content')
<div class="container dashboard-container">

@if(auth()->check() && strtolower(trim(auth()->user()->role)) === 'admin')
    {{-- ================= ADMIN DASHBOARD ================= --}}
    <h2 class="mb-4 text-center">👨‍💼 Admin Management Center</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card-box border-top border-primary border-4">
                <h4>Total Users</h4>
                <p>{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-box border-top border-success border-4">
                <h4>Total Tasks</h4>
                <p>{{ $tasksCount ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-box border-top border-warning border-4">
                <h4>Waitlist</h4>
                <p>{{ \App\Models\Waitlist::where('status', 'waiting')->count() }}</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-box border-top border-danger border-4">
                <h4>Complaints</h4>
                <p>{{ \App\Models\Complaint::where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="dashboard-actions mb-4 text-center">
        <a href="{{ route('users.index') }}" class="btn-dashboard">👥 Users List</a>
        <a href="{{ route('admin.waitlist') }}" class="btn-dashboard">📋 Waitlist Admin</a>
        <a href="{{ route('leases.index') }}" class="btn-dashboard">📜 All Leases</a>
        @if(auth()->user()->role === 'admin')
    <a href="{{ route('warden.inspections.index') }}" class="btn-dashboard">
        <i class="bi bi-shield-check"></i> Compliance Audits
    </a>
@endif
    </div>

    {{-- Activity Logs --}}
    <h3 class="section-title">📋 Recent Activity Logs</h3>
    <div class="card-box p-0 overflow-hidden">
        <table class="table table-hover mb-0">
            <thead class="table-light">
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
                    <td><span class="badge bg-info text-dark">{{ $log->action }}</span></td>
                    <td>{{ $log->model }}</td>
                    <td>ID: {{ $log->user_id }}</td>
                    <td>{{ $log->created_at->diffForHumans() }}</td>
                </tr>

                
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Complaints Section --}}
    <h3 class="section-title text-danger">⚠️ Urgent Complaints</h3>
    <div class="card-box p-0 overflow-hidden border border-danger">
        <table class="table align-middle mb-0">
            <thead class="table-danger">
                <tr>
                    <th>Farmer</th>
                    <th>Issue</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\Complaint::with(['user', 'plot'])->where('status', 'pending')->latest()->take(5)->get() as $complaint)
                <tr>
                    <td class="fw-bold">{{ $complaint->user->name }}</td>
                    <td>{{ Str::limit($complaint->title, 30) }}</td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td>
                        <form action="{{ route('complaints.resolve', $complaint->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Resolve</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-3">No pending complaints.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Damage & Incident Reports (Main Branch Work) --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <h3 class="section-title">🔧 Damage Reports</h3>
            @foreach($damages ?? [] as $d)
                <div class="report-card">
                    <p class="mb-1 fw-bold">{{ $d->description }}</p>
                    @if($d->image)
                        <img src="{{ asset('storage/' . $d->image) }}" width="100" class="rounded mb-2">
                    @endif
                    <div class="mt-2">
                        @if(!$d->fine)
                            <a href="/damage/{{ $d->id }}/fine" class="btn btn-warning btn-sm">Add Fine</a>
                        @else
                            <span class="badge bg-success">✔ Fine Applied</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-6">
            <h3 class="section-title text-warning">🚨 Incidents</h3>
            @foreach($incidents ?? [] as $i)
                <div class="report-card border-warning">
                    <p class="mb-1">{{ $i->description }}</p>
                    <span class="badge bg-danger">Severity: {{ $i->severity ?? 'N/A' }}</span>
                </div>
            @endforeach
        </div>
    </div>

@else
    {{-- ================= USER DASHBOARD ================= --}}
    <h2 class="mb-4 text-center">👤 My Garden Activity</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-box">
                <h4>Your Volunteer Hours</h4>
                <p>{{ auth()->user()->volunteerHours->sum('hours') ?? 0 }} hrs</p>
            </div>
        </div>
    </div>
    <div class="card-box mt-4">
        <h4>📌 Status Update</h4>
        <p class="text-muted fs-6">Check your plots and tasks from the navigation menu.</p>
    </div>
@endif

</div>
@endsection