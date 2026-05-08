@extends('layouts.app')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><i class="bi bi-receipt text-primary"></i> My Invoices</h3>
                <p class="text-subtitle text-muted">Track your plot rental payments and download receipts.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade mt-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <section class="section mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="table1">
                        <thead class="bg-light">
                            <tr>
                                <th>Invoice ID</th>
                                <th>Plot Number</th>
                                <th>Amount</th>
                                <th>Status</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Tenant Name</th>
                                @endif
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td><span class="fw-bold">#INV-{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                                    <td><span class="badge bg-info">Plot #{{ $invoice->plot->plot_number ?? 'N/A' }}</span></td>
                                    <td class="fw-bold text-success">{{ number_format($invoice->amount, 2) }} EGP</td>
                                    <td>
                                        @if($invoice->status == 'unpaid' || $invoice->status == 'pending')
                                            <span class="badge bg-light-danger text-danger">Pending</span>
                                        @else
                                            <span class="badge bg-light-success text-success">Paid</span>
                                        @endif
                                    </td>

                                    @if(auth()->user()->role === 'admin')
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-content bg-secondary text-white">{{ substr($invoice->user->name, 0, 1) }}</span>
                                                </div>
                                                <span>{{ $invoice->user->name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                    @endif

                                    <td class="text-center">
                                        {{-- زر تحميل الـ PDF متاح للكل --}}
                                        <a href="{{ route('invoices.download', $invoice->id) }}"
                                           class="btn btn-sm btn-outline-primary shadow-sm me-1" title="Download PDF">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>

                                        {{-- زرار الدفع: يظهر فقط لو الفاتورة مش مدفوعة والشخص هو صاحب الفاتورة --}}
                                        @if(($invoice->status == 'unpaid' || $invoice->status == 'pending') && auth()->id() === (int)$invoice->user_id)
                                            <form action="{{ route('invoices.pay', $invoice->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success shadow-sm"
                                                        onclick="return confirm('Confirm payment for this invoice?')">
                                                    <i class="bi bi-credit-card"></i> Pay Now
                                                </button>
                                            </form>
                                        @endif

                                        {{-- لو أدمن والفاتورة لسه مدفعتش، يظهرله تنبيه بصري بس --}}
                                        @if(auth()->user()->role === 'admin' && ($invoice->status == 'unpaid' || $invoice->status == 'pending'))
                                            <span class="badge bg-light-warning text-dark small">Waiting User</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        You don't have any invoices yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection