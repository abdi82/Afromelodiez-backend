@extends('admin_layout.layout')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('update_artist',$artist->id) }}" enctype='multipart/form-data' method="post">

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

                <input type="text" value="{{$artist->name}}" placeholder="Enter Arist Name"name="name">
                </div>
                <div>
                    <label for="CatgoryName"> Verify Status </label>
                   <select name="isVerified" id="status">
                    <option disabled="disabled" selected>None </option>
                    
                    <?php if($artist->isVerified == 1)
                    {?> 
                     <option value="1" selected>Active</option>
                    <?php }
                    else{ ?>
                    <option value="1">Active</option>
                    <?php }
                    ?>
                    <?php if($artist->isVerified == 0)
                    {?> 
                    <option value="0" selected>InActive</option>
                    <?php }
                    else{ ?>
                    <option value="0">InActive</option>
                    <?php }
                    ?>

                    
                   </select>
                </div>
                <div>
                <label for="CatgoryName">image</label>
                <input type="file" placeholder="Enter  Name" name="image">
                </div>
                <div class="col-md-12 ">
                    <?php 
                
                if($artist->image != '')
                    { ?>
                <img src="{{url('')}}/storage/artist/{{$artist->image}}" width="200" height="200">
                <?php } ?>
                </div>
                <div>
                <label for="CatgoryName">location</label>
                <input type="text" value="{{$artist->location}}" placeholder="Enter Artist Location"name="location">
                </div>
                <div>
                                    <label for="CatgoryName">Description</label>
                    <textarea name="description" rows="4" cols="50" value="{{$artist->description}}"> {{$artist->description}}
                     </textarea>
                </div>
                <div>
                    <label for="CatgoryName">Facebook</label>
                    <input type="text" value="{{$artist->facebook}}" placeholder="Enter Facebook"name="facebook">
                </div>
               <div>
                    <label for="CatgoryName">Twitter</label>
                    <input type="text"  value="{{$artist->twitter}}" placeholder="Enter Twitter"name="twitter">
                </div>
                <div>
                    <label for="CatgoryName">Youtube</label>
                    <input type="text" value="{{$artist->youtube}}" placeholder="Enter Youtube"name="youtube">
                </div>
                                <div>
                    <label for="CatgoryName">Instagram</label>
                    <input type="text" value="{{$artist->instagram}}" placeholder="Enter Instagram"name="instagram">
                </div>
                                <div>
                    <label for="CatgoryName">Snapchat</label>
                    <input type="text" value="{{$artist->snapchat}}" placeholder="Enter Snapchat"name="snapchat">
                </div>
                <input type="hidden" name="user_role" value="admin">
                <input type="submit" value="Save">

            </form>
        </div>
        <!-- <div class="left-Catgory-section">
            <a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Create New</a>
        </div> -->
    </div>

@endsection