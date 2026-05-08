@extends('layouts.app')

@section('content')
<style>
    .page-heading { margin-top: 80px; }
    .badge-vip { background: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
</style>

<div class="container page-heading">
    <h2 class="mb-4"> Sharing Management</h2>

    @if(auth()->user()->plots->count() > 0)
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-person-plus-fill"></i> Invite a Partner to Your Plot
        </div>
        <div class="card-body">
            <form action="{{ route('sharing.store') }}" method="POST">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Select Your Plot</label>
                        <select name="plot_id" class="form-select" required>
                            @foreach(auth()->user()->plots as $plot)
                                <option value="{{ $plot->id }}">Plot #{{ $plot->plot_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Partner's Email Address</label>
                        <input type="email" name="shared_with_email" class="form-control" placeholder="example@mail.com" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">SEND INVITE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card mb-5 border-info shadow-sm">
        <div class="card-header bg-info text-white">
            <i class="bi bi-envelope-open-fill"></i> Incoming Sharing Requests
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Plot #</th>
                        <th>Owner Name</th>
                        <th>Share Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receivedInvites as $invite)
                    <tr>
                        <td>#{{ $invite->plot->plot_number }}</td>
                        <td>{{ $invite->plot->owner->name ?? 'System' }}</td>
                        <td><span class="badge bg-light-info text-dark">Co-Farming</span></td>
                        <td>
                            <span class="badge {{ $invite->status == 'pending' ? 'bg-warning' : ($invite->status == 'accepted' ? 'bg-success' : 'bg-danger') }}">
                                {{ strtoupper($invite->status) }}
                            </span>
                        </td>
                        <td>
                            @if($invite->status == 'pending')
                                <div class="d-flex gap-2">
                                    <form action="{{ route('sharing.accept', $invite->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Accept ✅</button>
                                    </form>
                                    <form action="{{ route('sharing.reject', $invite->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-danger">Reject ❌</button>
                                    </form>
                                </div>
                            @else
                                <small class="text-muted">No actions available</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No pending requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-secondary shadow-sm">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-send-check-fill"></i> Sent Invitations History
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Plot #</th>
                        <th>Invited Partner</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sentInvites as $invite)
                    <tr>
                        <td>#{{ $invite->plot->plot_number }}</td>
                        <td>
                            {{ $invite->partner->name ?? 'Invited' }} 
                            <br><small class="text-muted">{{ $invite->partner->email ?? $invite->shared_with_email }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $invite->status === 'accepted' ? 'bg-success' : ($invite->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ strtoupper($invite->status) }}
                            </span>
                        </td>
                        <td>
                            @if($invite->status === 'pending')
                                <form action="{{ route('sharing.reject', $invite->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-link btn-sm text-danger p-0" onclick="return confirm('Cancel this invite?')">Cancel</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">You haven't sent any invitations yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection