@extends('layouts.app')

@section('content')
<style>
    .page-heading {
        margin-top: 100px; 
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

<div class="container page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark"><i class="bi bi-clock-history text-primary"></i> Pending Rental Requests</h3>
            <p class="text-muted small">Review and manage plot lease applications from farmers.</p>
        </div>
        <span class="badge bg-primary px-3 py-2 rounded-pill">{{ $pendingPlots->count() }} Total Requests</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Plot #</th>
                        <th>Requester Name</th>
                        <th>Area</th>
                        <th>Request Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingPlots as $plot)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary">#{{ $plot->plot_number }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle p-2 me-2">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                    {{ $plot->owner->name ?? 'Unknown User' }}
                                </div>
                            </td>
                            <td><i class="bi bi-geo-alt small"></i> {{ $plot->area_sqm }} sqm</td>
                            <td><i class="bi bi-calendar3 small"></i> {{ $plot->updated_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- زرار الموافقة --}}
                                    <form action="{{ route('plots.approve', $plot->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm px-3 rounded-pill">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>

                                    {{-- زرار الرفض - تم تعديل المتغير هنا من lease لـ plot --}}
                                    <form action="{{ route('plots.reject', $plot->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill" 
                                                onclick="return confirm('Are you sure you want to reject this request? The plot will become available again.')">
                                            <i class="bi bi-x-circle"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                    <h5 class="fw-light">No pending requests at the moment.</h5>
                                    <p class="small">All rental applications have been processed.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection