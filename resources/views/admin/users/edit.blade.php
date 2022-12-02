@extends('admin_layout.layout')
@section('content')

<div class="Catgory-section">
    <div class="left-Catgory-section">
        <form method="POST" action="{{ route('admin_update',$user->id)}}" enctype='multipart/form-data'>
            @csrf

                <div class="col-md-12 ">
                    <label for="CatgoryName">Enter Name</label>
                    <input type="text" placeholder="Enter Name" name="name" value="{{$user->name}}">
                </div>
                <div class="col-md-12 ">
                    <label for="CatgoryName">Email</label>
                    <input type="email" placeholder="Enter Email" name="email" value="{{$user->email}}" disabled="disabled">
                </div>
                <div class="col-md-12 ">
                    <label for="CatgoryName">Password </label>
                    <input type="password" placeholder="Password" name="password">
                </div>
                 <div class="col-md-12 "> 
                <input type="hidden" name="user_role" value="manager">
                <input type="submit" value="Save">
                </div>

         </form>
     </div>
 </div>
@endsection