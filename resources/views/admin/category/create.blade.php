<?php 
use App\Models\Category;
$catlist = Category::paginate(2);
 ?>
@extends('admin_layout.layout')
@section('content')

<div class="Catgory-section">
	<div class="left-Catgory-section">
<form action="{{ route('cat.store') }}" enctype='multipart/form-data' method="post">
    @if(session()->has('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
@csrf
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="col-md-12">
    <label for="CatgoryName">Category Name</label>
    <input type="text" placeholder="Enter Category Name"name="name">
    </div>
        <div class="col-md-12">
            <label> Select color </label>
            <input type="color" name="bg_color">
        </div>        
        <div class="col-md-12">
            <label for="BackGround ">Image</label>
            <input type="file" placeholder="" name="image">
        </div>
    <input type="submit" value="Save">

</form>
</div>
</div>
@endsection