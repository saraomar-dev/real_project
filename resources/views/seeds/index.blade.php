@extends('layouts.app')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index_users.css') }}">
@endsection


@section('content')
    @if (auth()->user()->role === 'admin')
        <div class="add-user-container">
            <a href="{{ route('seeds.create') }}" class="btn-add-user">
                Add seeds
            </a>
        </div>
    @endif
    <div class="table-container">
        <table class="table custom-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    @if (auth()->user()->role === 'admin') <th>Actions</th>@endif
                </tr>
            </thead>
            @foreach ($seeds as $seed)
                <tbody>
                    <tr>
                        <td>{{ $seed->name }}</td>
                        <td>{{ $seed->quantity }}</td>
                        <td>{{ $seed->expiry_date }}</td>
                        <td>
                            @if ($seed->expiry_date < now())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($seed->expiry_date <= now()->addDays(7))
                                <span class="badge bg-warning text-dark">Expiring Soon</span>
                            @else
                                <span class="badge bg-success">Available</span>
                            @endif
                        </td>


                        <td>

                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('seeds.edit', $seed->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('seeds.destroy', $seed->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-edit">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                </tbody>
            @endforeach
        </table>
    </div>
@endsection


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
@endsection
