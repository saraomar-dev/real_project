@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px;">
    <div class="page-heading d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            <i class="bi bi-file-earmark-text text-primary"></i> 
            @if(auth()->user()->role === 'admin') 
                All Rental Leases | 
            @else 
                My Rental Leases |
            @endif
        </h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">Active & Past Leases</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Plot #</th>
                            @if(auth()->user()->role === 'admin') 
                                <th>Tenant (المستأجر)</th> 
                            @endif
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leases as $lease)
                        <tr>
                            <td class="ps-4 fw-bold">Plot #{{ $lease->plot->plot_number ?? 'N/A' }}</td>
                            
                            @if(auth()->user()->role === 'admin') 
                                <td><span class="badge bg-light text-dark border">{{ $lease->user->name ?? 'Unknown' }}</span></td>
                            @endif

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
                                    {{-- صلاحيات الأدمن فقط للتحكم في العقود بناءً على الـ SRS --}}
                                    @if(auth()->user()->role === 'admin' && $lease->status == 'active')
                                        <form action="{{ route('leases.renew', $lease->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="return confirm('Are you sure you want to renew this lease for one more year?')">
                                                <i class="bi bi-arrow-repeat"></i> Renew
                                            </button>
                                        </form>

                                        <form action="{{ route('leases.terminate', $lease->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Are you sure you want to terminate this lease?')">
                                                <i class="bi bi-x-circle"></i> Terminate
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small italic">View Only</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No leases found. | لا توجد عقود إيجار مسجلة حالياً.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection