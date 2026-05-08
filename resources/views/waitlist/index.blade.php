@extends('layouts.app')

<style>
    .page-heading {
        margin-top: 80px;
    }
    .alert {
        border-radius: 12px;
        border: none;
    }
</style>

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
<div class="container page-heading mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="section-title">Waitlist Management</h3>
        <div class="stats-badge">
            <span class="badge rounded-pill badge-primary p-2 px-3">
                Current Waiting: {{ $waitlist->count() }}
            </span>
        </div>
    </div>

    {{-- --- رسايل التنبيه (Alerts) --- --}}
    @if(session('error'))
        <div class="alert alert-danger shadow-sm mb-4">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    {{-- -------------------------- --}}

    <div class="table-container card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table custom-table align-middle mb-0 bg-white">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Rank</th>
                            <th>User Name</th>
                            <th>Karma Points</th>
                            <th>Joined At</th>
                            <th>Priority Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($waitlist as $item)
                            <tr>
                                <td>
                                    <span class="badge {{ $loop->first ? 'badge-warning' : 'badge-light text-dark' }} rounded-pill">
                                        #{{ $loop->iteration }}
                                    </span>
                                </td>
                                <td>
                                    <p class="fw-bold mb-1">{{ $item->user->name }}</p>
                                    <small class="text-muted">{{ $item->user->email }}</small>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        {{ $item->user->karma_points ?? '0' }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge badge-info">{{ number_format($item->priority_score, 1) }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.waitlist.assign', $item->id) }}" method="POST">
                                        @csrf
                                        @php
                                            $availablePlotsCount = \App\Models\Plot::where('status', 'available')->count();
                                        @endphp

                                        <button type="submit" class="btn btn-success btn-sm rounded-pill shadow-0" {{ $availablePlotsCount == 0 ? 'disabled' : '' }}>
                                            {{ $availablePlotsCount == 0 ? 'No Plots' : 'Assign Plot' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">The waitlist is currently empty.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
@endsection