<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!--<title>Registration Form in HTML CSS</title>-->
    <!---Custom CSS File--->
    <link rel="stylesheet" href="{{ asset('css/edit_users.css') }}">
</head>

<body>
    <section class="container">
        <header>edit seed</header>
        <form action="{{ route('seeds.update', $seed->id) }}" class="form" method='POST'>
            @csrf
            @method('PUT')
            <div class="input-box">
                <label> Name</label>
                <input type="text" placeholder="Enter name" name='name' value='{{ $seed->name }}'
                    required />
            </div>
            @error('name')
                <p class="text-danger"> {{ $message }} </p>
            @enderror
            <div class="input-box">
                <label> quantity</label>
                <input type="text" placeholder="Enter quantity" name='quantity' value='{{ $seed->quantity }}'
                    required />
            </div>
            @error('quantity')
                <p class="text-danger"> {{ $message }} </p>
            @enderror
            <div class="input-box">
                <label> Expiry Date</label>
                <input type="date" placeholder="Enter Expiry Date" name='expiry_date'
                    value='{{ $seed->expiry_date }}' />
            </div>
            @error('expiry_date')
                <p class="text-danger"> {{ $message }} </p>
            @enderror
            <button type="submit">Update</button>
        </form>
    </section>
</body>

</html>
