@extends('layouts.app')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection


@section('content')

<h3 class="section-title">aduit logs</h3>
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
            <td>{{ $log->user_id ?? 'Guest' }}</td>
            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
        </tr>

         @endforeach
      
    </table>
    {{ $logs->links() }}
</div>

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
@endsection
