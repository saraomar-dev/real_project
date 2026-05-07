<!DOCTYPE html>
<html>
<head>
    <title>Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2 class="text-center mb-4">🌿 Marketplace</h2>

    <!-- ⭐ Karma -->
    <div class="alert alert-info text-center">
        ⭐ Your Karma Points: {{ auth()->user()->karma ?? 0 }}
    </div>

    <!-- 👤 USER VIEW -->
    @if(auth()->user()->role == 'user')

        <!-- Add Product -->
        <div class="card p-3 mb-4">

            <h5>Add Product</h5>

            <form method="POST" action="/marketplace">
                @csrf

                <input type="text" name="product_name" class="form-control mb-2" placeholder="Product Name">

                <input type="number" name="quantity" class="form-control mb-2" placeholder="Quantity">

                <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

                <input type="date" name="deadline" class="form-control mb-2">

                <button class="btn btn-success w-100">➕ Add Product</button>
            </form>

        </div>

        <!-- My Products -->
        <h4>📦 Available Products</h4>

        @foreach($items as $item)

            <div class="card p-3 mb-2">

                <h5>{{ $item->product_name }}</h5>
                <p>Quantity: {{ $item->quantity }}</p>
                <p>{{ $item->description }}</p>
                <p>Deadline: {{ $item->deadline }}</p>

            </div>

        @endforeach

    @endif



    <!-- 👨‍💼 ADMIN VIEW -->
    @if(auth()->user()->role == 'admin')

        <h4>📊 All Marketplace Data</h4>

        @foreach($items as $item)

            <div class="card p-3 mb-2">

                <h5>{{ $item->product_name }}</h5>
                <p>Quantity: {{ $item->quantity }}</p>
                <p>Owner ID: {{ $item->user_id }}</p>
                <p>{{ $item->description }}</p>
                <p>Deadline: {{ $item->deadline }}</p>

            </div>

        @endforeach

    @endif

</div>

</body>
</html>