@extends('layouts.app')

@section('content')
<style>
    .page-heading { margin-top: 80px; }
    .badge-vip { background: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb; }
</style>

@php
    $user = auth()->user();
    $isAdminOrWarden = $user && ($user->role == 'admin' || $user->role == 'warden');
    $isFull = \App\Models\Plot::where('status', 'available')->count() == 0;
    
    $waitlistEntry = $user
        ? \App\Models\Waitlist::where('user_id', $user->id)->where('status', 'waiting')->first()
        : null;

    $isStaff = $isAdminOrWarden;
    $hasActivePlot = false; // لو عندك logic بيحسب ده ضيفيه هنا
@endphp

<div class="page-heading container">

    {{-- Waitlist Section --}}
    @if($isFull && $user && !$isAdminOrWarden)
        <div class="alert alert-warning mt-3 shadow-sm border-0">
            @if(!$waitlistEntry)
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>The garden is full!</strong> Join the waitlist now.
                <form action="{{ route('waitlist.store') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Join Waitlist</button>
                </form>
            @else
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-2"></i><strong>You are on the waitlist!</strong></span>
                    <form action="{{ route('waitlist.destroy', $waitlistEntry->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Leave Waitlist</button>
                    </form>
                </div>
            @endif
        </div>
    @endif

    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Garden Plots Management</h3>
                <p class="text-subtitle text-muted">Manage and monitor all available land units.</p>
            </div>
            <div class="col-12 col-md-6 text-end">
                @if($user && method_exists($user, 'isAdmin') && $user->isAdmin())
                    <a href="{{ route('admin.requests') }}" class="btn btn-warning shadow-sm">
                        <i class="bi bi-bell"></i> Pending: {{ \App\Models\Plot::where('status', 'pending')->count() }}
                    </a>
                    <a href="{{ route('plots.create') }}" class="btn btn-primary shadow-sm ms-2">
                        <i class="bi bi-plus-circle"></i> Add New Plot
                    </a>
                @endif
            </div>
        </div>
    </div>

    <section class="section mt-4">
        <div class="row">
            @foreach($plots as $plot)
            <div class="col-xl-4 col-md-6 col-sm-12 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <img src="{{ $plot->image ? asset($plot->image) : asset('assets/images/samples/origami.jpg') }}"
                             class="card-img-top img-fluid" style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <h5 class="card-title">Plot Number: {{ $plot->plot_number }}</h5>

                        @php
                            $isActualPartner = \App\Models\PlotShare::where('plot_id', $plot->id)
                                                ->where('shared_with', auth()->id())
                                                ->where('status', 'accepted')->exists();
                        @endphp

                        <div class="mb-2">
                            @if($plot->user_id == auth()->id())
                                <span class="badge bg-primary"><i class="bi bi-person-badge"></i> Owner</span>
                            @elseif($isActualPartner)
                                <span class="badge bg-info text-white"><i class="bi bi-people"></i> Partner</span>
                            @endif
                        </div>

                        <p class="card-text mb-2">
                            <span class="badge badge-vip"><i class="bi bi-fullscreen"></i> Area: {{ $plot->area_sqm }} sqm</span>
                        </p>

                        <div class="mb-3">
                            @if($plot->status === 'rented')
                                <span class="badge bg-light-danger text-danger d-block w-100 text-start py-2 mb-1">
                                    <i class="bi bi-lock-fill"></i> Status: Rented
                                </span>

                                {{-- Monitoring for Admin/Warden --}}
                                @if($isAdminOrWarden)
                                    <div class="p-2 border rounded bg-light mb-1">
                                        @if($plot->seed_id)
                                            <span class="text-success d-block small"><i class="bi bi-flower1"></i> Growing: <strong>{{ $plot->seed->name }}</strong></span>
                                        @else
                                            <span class="text-muted d-block small"><i class="bi bi-patch-exclamation"></i> Not planted yet</span>
                                        @endif
                                        <span class="text-dark d-block small mt-1"><i class="bi bi-person-check"></i> Occupied by: {{ $plot->owner->name ?? 'N/A' }}</span>
                                    </div>
                                @endif

                            @elseif($plot->status === 'pending')
                                <span class="badge bg-light-warning text-warning d-block w-100 text-start py-2">
                                    <i class="bi bi-hourglass-split"></i> Pending Approval
                                </span>
                            @else
                                <span class="badge bg-light-success text-success d-inline-block py-2">
                                    <i class="bi bi-check-circle"></i> Status: Available
                                </span>
                            @endif
                        </div>

                        <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ $plot->location_tag ?? 'Main Garden Sector' }}</small>
                    </div>

                    <div class="card-footer d-flex flex-wrap gap-2 bg-transparent border-top-0 pb-3">
                        <a href="{{ route('plots.show', $plot->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">View Details</a>

                        @if($plot->status === 'rented' && $user && $user->id == $plot->user_id)
                            <a href="{{ route('leases.index') }}" class="btn btn-sm btn-info text-white flex-grow-1">My Lease</a>
                        @endif

                        @if($plot->status === 'available' && $user && !$isStaff && !$hasActivePlot)
                            <a href="{{ route('plots.show', $plot->id) }}" class="btn btn-sm btn-success flex-grow-1">Rent Now</a>
                        @endif

                        @if($user && $user->role === 'admin')
                            <a href="{{ route('plots.edit', $plot->id) }}" class="btn btn-sm btn-warning flex-grow-1">Edit</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection