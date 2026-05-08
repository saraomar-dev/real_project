@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px;">
    <div class="card shadow border-0 p-4">
        <h4 class="text-danger mb-4"><i class="bi bi-exclamation-triangle"></i> Report an Issue - Plot #{{ $plot->plot_number }}</h4>
        
        {{-- التعديل في السطر ده بس --}}
<form action="{{ route('complaints.store') }}" method="POST">
    @csrf
    {{-- باقي الحقول زي ما هي --}}
            <input type="hidden" name="plot_id" value="{{ $plot->id }}">

            <div class="mb-3">
                <label class="form-label fw-bold">Issue Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g., Water leakage, Broken fence" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Problem Details</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Describe the problem in detail..." required></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-danger px-4">SEND REPORT</button>
                <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection