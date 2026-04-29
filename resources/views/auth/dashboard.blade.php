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

    <div class="card-box">
        <h4>Other Roles</h4>
        {{-- <p>{{ $others }}</p> --}}
        <p>not yet</p>
    </div>

</div>
<div class="dashboard-actions">
    <a href="{{ route('users.index') }}" class="btn-dashboard">
        👥 Manage Users
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

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
@endsection
