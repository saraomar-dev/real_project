@extends('layouts.app') 

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h3 class="mb-0 fw-bold text-primary">Registered Plots List</h3>
            <a href="{{ route('plots.create') }}" class="btn btn-primary shadow-sm">
                Add New Plot
            </a>
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Plot Number</th>
                            <th>Area (sqm)</th>
                            <th>Soil Quality</th>
                            <th class="text-center">Sunlight</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plots as $plot)
                        <tr>
                            <td class="fw-bold">{{ $plot->plot_number }}</td>
                            <td>{{ $plot->area_sqm }} m²</td>
                            <td>{{ ucfirst($plot->soil_quality) }}</td>
                            <td class="text-center">{{ $plot->sunlight_exposure }}%</td>
                            <td class="text-center">
                                <span class="badge {{ $plot->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($plot->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('plots.edit', $plot->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                                    
                                    <form action="{{ route('plots.destroy', $plot->id) }}" method="POST" onsubmit="return confirm('Delete this plot?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($plots->isEmpty())
                <div class="text-center py-5 text-muted bg-light border rounded">
                    <p class="mb-0 italic">No records found. Click "Add New Plot" to get started.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection