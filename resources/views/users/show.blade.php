@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/show_users.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
@endsection
@section('content')
<!DOCTYPE html>
<!-- Created By CodingNepal - www.codingnepalweb.com -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Neumorphism Profile Card | CodingNepal</title>
</head>
<body>
  <div class="wrapper">
    {{-- <div class="img-area">
      <div class="inner-area">
        <img src="https://images.unsplash.com/photo-1492288991661-058aa541ff43?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60" alt="">
      </div>
    </div> --}}
    {{-- <div class="icon arrow"><i class="fas fa-arrow-left"></i></div>
    <div class="icon dots"><i class="fas fa-ellipsis-v"></i></div> --}}
    <div class="name">profile</div>

    <div class="social-icons">
      {{-- <a href="#" class="fb"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
      <a href="#" class="insta"><i class="fab fa-instagram"></i></a>
      <a href="#" class="yt"><i class="fab fa-youtube"></i></a> --}}
      <div class="about">name : {{$user->name}}</div>
      <div class="about">email : {{$user->email}}</div>
      @if ($user->phone=== NULL)
      <div class="about">phone : there is no num yet ,please edit your profile and add num</div>
       @else  
        <div class="about">phone : {{$user->phone}}</div>
      @endif
      <div class="about">role : {{$user->role}}</div>
  
    </div>
    <div class="buttons">
           @if(auth()->user()->id===$user->id)
          <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">
        Edit
    </a>
           @endif
      {{-- <button>Subscribe</button> --}}
    </div>
    {{-- <div class="social-share">
      <div class="row">
        <i class="far fa-heart"></i>
        <i class="icon-2 fas fa-heart"></i>
        <span>20.4k</span>
      </div>
      <div class="row">
        <i class="far fa-comment"></i>
        <i class="icon-2 fas fa-comment"></i>
        <span>14.3k</span>
      </div>
      <div class="row">
        <i class="fas fa-share"></i>
        <span>12.8k</span>
      </div>
    </div> --}}
  </div>
</body>
</html>
@endsection