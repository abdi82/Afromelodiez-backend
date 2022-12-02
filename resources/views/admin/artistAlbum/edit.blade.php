@extends('admin_layout.layoutHome')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('update_album_artist',$album->id) }}" enctype='multipart/form-data' method="post">

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

                <input type="text" value="{{$album->name}}" placeholder="Enter Album Name" name="name">
                </div>
                <div>
                <label for="CatgoryName">Upload Album Image</label>
                <input type="file" value="{{$album->image}}" placeholder="Upload Album Image" name="image">
                </div>
                <div>
                    <?php 
                
                if($album->image != '')
                    { ?>
                <img src="{{url('')}}/storage/album/{{$album->image}}" width="200" height="200">
                <?php } ?>
                </div>
                <input type="submit" value="Save">

            </form>
        </div>
    </div>

@endsection