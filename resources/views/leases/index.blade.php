@extends('layouts.app')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <h3>My Rental Leases | عقود الإيجار الخاصة بي</h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="card-title mb-0">Active & Past Leases</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Plot #</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leases as $lease)
                    <tr>
                        <td class="fw-bold">Plot #{{ $lease->plot->plot_number ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($lease->start_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($lease->end_date)->format('Y-m-d') }}</td>
                        <td>
                            @if($lease->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($lease->status == 'terminated')
                                <span class="badge bg-secondary">Terminated</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ ucfirst($lease->status) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                @if($lease->status == 'active')
                                    <form action="{{ route('leases.renew', $lease->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" onclick="return confirm('Are you sure you want to renew this lease for one more year?')">
                                            <i class="bi bi-arrow-repeat"></i> Renew
                                        </button>
                                    </form>

                                    <form action="{{ route('leases.terminate', $lease->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to terminate this lease?')">
                                            <i class="bi bi-x-circle"></i> Terminate
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No leases found. | لا توجد عقود إيجار مسجلة حالياً.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection