@extends('admin_layout.layoutHome')
@section('content')

<div class="Catgory-section">
	<div class="left-Catgory-section">
<form action="{{ route('podcast.store_artist') }}" enctype='multipart/form-data' method="post">
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
    <input type="text" placeholder="Podcast Name" name="name">
    </div>
    <div class="col-md-12 ">
    <label for="CatgoryName"> BG Color </label>
    <input type="color" name="bg_color">
    </div>
    <div class="col-md-12 ">
    <label for="CatgoryName">Image</label>
    <input type="file" name="image">
    </div> 
    <div class="col-md-12 ">
    <label for="CatgoryName"> Release Date </label>
    <input type="date" name="release_date">
    </div> 
    <input type="hidden" name="artist_id" value="<?php echo ucfirst(auth()->user()->artist_id); ?>">

    <div class="col-md-12 ">
    <input type="submit" value="Save">
     </div>               
</form>
</div>
<!-- <div class="left-Catgory-section">
	<a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Create New</a>
</div> -->
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script type="text/javascript">
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
        placeholder: 'Select Language',
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
        placeholder: 'Select Category',
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