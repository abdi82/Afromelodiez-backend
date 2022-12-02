@extends('admin_layout.layout')
@section('content')

<div class="Catgory-section">
    <div class="left-Catgory-section">
        <form method="POST" action="{{ route('Register_admin')}}" enctype='multipart/form-data'>
            @csrf

                <div class="col-md-12 ">
                    <label for="CatgoryName">Enter Name</label>
                    <input type="text" placeholder="Enter Name" name="name" required>
                </div>
                <div class="col-md-12 ">
                    <label for="CatgoryName">Email</label>
                    <input type="email" placeholder="Enter Email" name="email" required>
                </div>
                <div class="col-md-12 ">
                    <label for="CatgoryName">Password </label>
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                 <div class="col-md-12 "> 
                <input type="hidden" name="user_role" value="manager">
                <input type="submit" value="Save">
                </div>

         </form>
     </div>
 </div>
@endsection