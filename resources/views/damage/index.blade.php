@extends('layouts.app')

@section('content')

<h2>Damage Reports</h2>

@foreach($reports as $report)

    <div class="card p-3 mb-2">
        <p>{{ $report->description }}</p>

        @if($report->image)
            <img src="{{ asset('storage/' . $report->image) }}" width="100">
        @endif

        @if(auth()->user()->role === 'admin')

            @if(!$report->fine)
                <a href="/damage/{{ $report->id }}/fine" class="btn btn-warning">
                    Add Fine
                </a>
            @else
                <span class="text-success">Fine Added</span>
            @endif

        @endif
    </div>

@endforeach

@endsection