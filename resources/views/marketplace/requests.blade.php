@extends('layouts.tools')

@section('content')

<div class="container mt-4">

    <h2 class="mb-4 text-primary">Trade Requests</h2>

    @foreach($requests as $req)
        <div class="card shadow-sm mb-3 p-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5>Listing ID: {{ $req->listing_id }}</h5>
                    <p class="mb-1">Requester ID: {{ $req->requester_id }}</p>

                    <span class="badge 
                        @if($req->status == 'pending') bg-warning
                        @elseif($req->status == 'accepted') bg-success
                        @else bg-danger
                        @endif
                    ">
                        {{ $req->status }}
                    </span>
                </div>

                <div class="d-flex gap-2">

                    <form method="POST" action="/trade/{{ $req->id }}/accept">
                        @csrf
                        <button class="btn btn-success btn-sm">Accept</button>
                    </form>

                    <form method="POST" action="/trade/{{ $req->id }}/reject">
                        @csrf
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </form>

                </div>

            </div>

        </div>
    @endforeach

</div>

@endsection