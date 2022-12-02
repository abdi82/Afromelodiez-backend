<?php 
use App\Models\Category;
$catlist = Category::paginate(2);
 ?>
@extends('admin_layout.layout')
@section('content')
<div class="Catgory-section">
	<div class="left-Catgory-section">
<form action="{{ route('cat.update',$category->id) }}" method="post" enctype='multipart/form-data'>
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
<div class="col-md-12 ">
<label for="CatgoryName">Category Name</label>
<input type="text" placeholder="category" name="name" value="{{$category->name}}">
</div>
        <div class="col-md-12 ">
            <label for="BackGround ">Back Ground Color</label>
            <input type="color" value="{{$category->bg_color}}" name="bg_color">
        </div>
        <div class="col-md-12 ">
            <label for="BackGround ">Image</label>
            <input type="file" name="image">
        </div>
            <div class="col-md-12 ">
        <?php 
    
    if($category->image != '')
        { ?>
    <img src="{{url('')}}/storage/category/{{$category->image}}" width="200" height="200">
    <?php } ?>
    </div>
        
    <input type="submit" value="Update">

</form>
</div>
</div>

@endsection