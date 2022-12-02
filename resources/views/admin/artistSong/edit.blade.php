@extends('admin_layout.layoutHome')
@section('content')

<div class="Catgory-section">
    <div class="left-Catgory-section">
        <?php 
        // echo "<pre>";
        // print_r($song_data);
        // die; 
        ?>
<form action="{{ route('update_song_artist',$song_data->id) }}" enctype='multipart/form-data' method="post">
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
    <label for="MusicName">Song name</label>
    <input type="text" placeholder="Song Name" name="name" value="{{$song_data->name}}">
    </div>
        <div class="col-md-12 ">
    <label for="songName">Mp3</label>
    <input type="file" name="song" value="{{$song_data->song}}">

    </div> 
    <div class="col-md-12 ">
     <p> Mp3: {{$song_data->song}} </p>
    <audio controls>
  <source src="{{url('')}}/storage/song/{{$song_data->song}}" type="audio/ogg">
  </audio>
    </div>
    <div class="col-md-12 ">
    <label for="songName">Mp4 (Video) </label>
    <input type="file" name="video" value="{{$song_data->video}}">

    </div> 
    <div class="col-md-12 ">
     <p> Mp4: {{$song_data->video}} </p>
    </div>
    <div class="col-md-12 ">
        <video width="320" height="240" controls>
          <source src="{{url('')}}/storage/video/{{$song_data->video}}" type="video/mp4">
        </video>
    </div>
	    <div class="col-md-12">
            <label for="ArtistName">Artist name</label>
            <select class="livesearch form-control p-3" name="artist_id" value="{{$song_data->artist_id}}"></select>
        </div>
        <div class="col-md-12 ">
        Artist Name: {{$artist_id_data}} 
        </div>
		<div class="col-md-12">
            <label for="LanguageName">Language name</label>
            <select class="langsearch form-control p-3" name="language_id" value="{{$song_data->language_id}}"> </select>
        </div>
        <div class="col-md-12 ">
        Language Name: {{$language_id_data}} 
        </div>
        <div class="col-md-12 ">
            <label for="CategoryName">Category </label>
            <select class="catsearch form-control p-3" name="category_id" value="{{$song_data->category_id}}"></select>
        </div>
        <div class="col-md-12 ">
        Category Name: {{$category_id_data}} 
        </div>
        <div class="col-md-12">
            <label for="AlbumName">Album Name</label>
            <select class="albumsearch form-control p-3" name="album" value="{{$song_data->album}}"> </select>
        </div>
        <div class="col-md-12 ">
        Album Name: {{$album_id_data}} 
        </div>
    <div class="col-md-12 ">
    <label for="CatgoryName">Song Image</label>
    <input type="file" name="song_image">

    </div>
    <div class="col-md-12 ">
        <?php 
    
    if($song_data->song_image != '')
        { ?>
    <img src="{{url('')}}/storage/song/images/{{$song_data->song_image}}" width="200"                 height="200">
    <?php } ?>
    </div>
	<div class="col-md-12">
    <label for="Lyrics">Lyrics</label>
	<textarea id="Lyrics" name="Lyrics" rows="4" cols="50" value="{{$song_data->lyrics}}"> {{$song_data->lyrics}}
        </textarea>
		</div>
    <div class="col-md-12">
        <label for="Release">Release Date</label>
        <input type="date" name="release_date" id="Release" value="{{$song_data->release_date}}">
    </div>    
    <div class="col-md-12 ">
    <input type="submit" value="Save">
    </div>
</form>
</div>

</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script type="text/javascript">
     $( document ).ready(function() {
      $("input[name='category_id']").val('test');

        console.log( "document loaded" );
    });              

    $('.livesearch').select2({
        placeholder: 'Select Artist',
        ajax: {
            url: '/ajax-autocomplete-search',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        console.log(item.id);
                        $("input[name='category_id']").val(item.id);
                        return {
                            text: item.name,
                            id: item.id,
                            value: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('.langsearch').select2({
        placeholder: 'Select Song',
        ajax: {
            url: '/ajax-autocomplete-search-language',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        $("input[name='artist_id']").val(item.id);
                        return {
                            text: item.name,
                            id: item.id,
                            value: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });  
     $('.catsearch').select2({
        placeholder: 'Select Song',
        ajax: {
            url: '/ajax-autocomplete-search-cat',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        $("input[name='language_id']").val(item.id);
                        return {
                            text: item.name,
                            id: item.id,
                            value: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });
	    $('.albumsearch').select2({
        placeholder: 'Select Album',
        ajax: {
            url: '/ajax-autocomplete-search-album',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        $("input[name='album']").val(item.id);
                        return {
                            text: item.name,
                            id: item.id,
                            value: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
@endsection