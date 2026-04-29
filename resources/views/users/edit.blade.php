
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
      <header>edit profile</header>
      <form action="{{route('users.update',$user->id)}}" class="form" method='POST'>
      @csrf
      @method('PUT')
        <div class="input-box">
          <label> Name</label>
          <input type="text" placeholder="Enter full name" name='name' value='{{$user->name}}' required />
        </div>
        <div class="input-box">
          <label> Email Address</label>
          <input type="text" placeholder="Enter email address" name='email' value='{{$user->email}}' required />
        </div>
         <div class="input-box">
          <label> phone num</label>
          <input type="text" placeholder="Enter email address" name='phone' value='{{$user->phone}}' />
        </div>
       @if(
    auth()->user()->role === 'admin' &&
    auth()->user()->id !== $user->id &&
    $user->role !== 'admin'
)
    <div class="input-box">
        <label> role</label>
        <input type="text" name="role" value="{{ $user->role }}" required />
    </div>
@endif
         <button type="submit">Update</button>
      </form>
    </section>
  </body>
</html>