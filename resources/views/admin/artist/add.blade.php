@extends('admin_layout.layout')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('add_artist') }}" enctype='multipart/form-data' method="post">

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

                <div>
                    <label for="CatgoryName">Name</label>
                    <input type="text" placeholder="Enter Arist Name"name="name">
                </div>


                    <input type="hidden" placeholder="Enter Email" value="1" name="isVerified" >

               
                <div>
                    <label for="CatgoryName">image</label>
                    <input type="file" placeholder="Enter  Name"name="image">
                </div>
                <div>
                    <label for="CatgoryName">location</label>
                    <input type="text" placeholder="Enter Artist Location"name="location">
                </div>
                <div>
                    <label for="CatgoryName">Description</label>
                    <textarea name="description" rows="4" cols="50">
                     </textarea>
                </div>
                <div>
                    <label for="CatgoryName">Facebook</label>
                    <input type="text" placeholder="Enter Facebook"name="facebook">
                </div>
                <div>
                    <label for="CatgoryName">Twitter</label>
                    <input type="text" placeholder="Enter Twitter"name="twitter">
                </div>
                <div>
                    <label for="CatgoryName">Youtube</label>
                    <input type="text" placeholder="Enter Youtube"name="youtube">
                </div>
                <div>
                    <label for="CatgoryName">Instagram</label>
                    <input type="text" placeholder="Enter Instagram"name="instagram">
                </div>
                <div>
                    <label for="CatgoryName">Snapchat</label>
                    <input type="text" placeholder="Enter Snapchat"name="snapchat">
                </div>
                <input type="hidden" name="user_role" value="admin">
                <input type="submit" value="Save">

            </form>
        </div>
    </div>

@endsection