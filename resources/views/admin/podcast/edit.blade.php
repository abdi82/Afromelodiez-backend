@extends('admin_layout.layout')
@section('content')

<div class="Catgory-section">
    <div class="left-Catgory-section">
        <?php 
        // echo "<pre>";
        // print_r($song_data);
        // die; 
        ?>
<form action="{{ route('update_podcast',$podcast->id) }}" enctype='multipart/form-data' method="post">
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
    <label for="CatgoryName">Podcast name</label>
    <input type="text" placeholder="Song Name"name="name" value="{{$podcast->name}}">
    </div>
    <div class="col-md-12">
            <label for="ArtistName">Artist name</label>
            <select class="livesearch form-control p-3" name="artist_id" value="{{$podcast->artist_id}}"></select>
    </div>
    <div class="col-md-12 ">
        Artist Name: {{$artist_id_data}} 
    </div>
        <div class="col-md-12 ">
    <label for="CatgoryName"> BG Color </label>
    <input type="color" name="bg_color" value="{{$podcast->bg_color}}">
    </div>
    <div class="col-md-12 ">
    <label for="CatgoryName">Image</label>
    <input type="file" name="image">
    </div> 
    <?php if($podcast->image != '') {  
       //$path=base_url()."/storage/podcast/{{$podcast->image}}";
        ?>
    <div class="col-md-12 ">

     <img src="{{url('')}}/storage/podcast/{{$podcast->image}}" width="200" height="200">
    </div>  
    <?php } ?> 
    <div class="col-md-12 ">
    <label for="CatgoryName"> Release Date </label>
    <input type="date" name="release_date" value="{{$podcast->release_date}}">
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