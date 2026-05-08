@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container py-5" style="margin-top: 100px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-5">
            <h3 class="mb-4 text-success"><i class="bi bi-seedling"></i> Start Planting in Plot #{{ $plot->plot_number }}</h3>
            
            <form action="{{ route('plots.plant', $plot->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">What would you like to grow?</label>
                    <select name="seed_id" class="form-select form-select-lg">
                        <option value="">-- Select a crop --</option>
                        @foreach($seeds as $seed)
                            <option value="{{ $seed->id }}">{{ $seed->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success btn-lg">Start Planting Now</button>
            </form>
        </div>
    </div>
</div>
@endsection