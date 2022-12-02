@extends('admin_layout.layoutHome')
@section('content')

<div class="Catgory-section">
	<div class="left-Catgory-section">
 <?php $adminrole=ucfirst(auth()->user()->user_role); ?>       
<form action="{{ route('store_artist') }}" enctype='multipart/form-data' method="post">
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
    <label for="CatgoryName">Song name</label>
    <input type="text" placeholder="Song Name"name="name">
    </div>
        <div class="col-md-12 ">
    <label for="CatgoryName">Mp3</label>
    <input type="file" name="song" id="Mysong">
    <audio id="audio"> </audio> 
    </div>
     <input type="hidden" name="duration" id="duration">
    <div class="col-md-12 ">
    <label for="CatgoryName">Mp4 (video) </label>
    <input type="file" name="video" id="Myvideo">
    </div>
        <div class="col-md-12">
            <label for="LaguageName">Language name</label>
            <select class="langsearch form-control p-3" name="language_id"></select>
        </div>
        <div class="col-md-12">
            <label for="CatgoryName">Album Name</label>
            <select class="albumsearch form-control p-3" name="album"> </select>
        </div>
    <div class="col-md-12 ">
    <label for="CatgoryName">Song Image</label>
    <input type="file" name="song_image">
    </div> 
	<div class="col-md-12">
            <label for="Lyrics">Lyrics </label>
	<textarea id="Lyrics" name="Lyrics" rows="4" cols="50"> 
        </textarea>
		</div> 
    <div class="col-md-12">
        <label for="Release">Release Date</label>
        <input type="date" name="release_date" id="Release">
    </div>    
    <input type="hidden" name="artist_id" value="<?php echo ucfirst(auth()->user()->artist_id); ?>">
    <div class="col-md-12 ">
    <input type="submit" value="Save" id="btnSubmit">
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

 var objectUrl;

$("#audio").on("canplaythrough", function(e){
    var seconds = e.currentTarget.duration;
    var duration = moment.duration(seconds, "seconds");
    
    var time = "";
    var hours = duration.hours();
    if (hours > 0) { time = hours + ":" ; }
    
    time = time + duration.minutes() + ":" + duration.seconds();
      var parts = time.split(':'),
        minutes = +parts[0],
        seconds = +parts[1];
    Seconds =(minutes * 60 + seconds).toFixed(3);
    var result = Seconds.split('.');
    $("#duration").val(result[0]); 
    
    URL.revokeObjectURL(objectUrl);
});

 $("#Mysong").change(function () {
                  var file = e.currentTarget.files[0];

                  objectUrl = URL.createObjectURL(file);
                    $("#audio").prop("src", objectUrl);

                var extension = $(this).val().split('.').pop().toLowerCase();

                var validFileExtensions = ['mp3', 'wav', 'mpeg'];
                if ($.inArray(extension, validFileExtensions) == -1) {
                    alert("Sorry!! Upload only mp3, mpeg, wav file");
                    $('#btnSubmit').prop('disabled', true);
                }
                else {
                    $('#btnSubmit').prop('disabled', false);
                }
            });
 $("#Myvideo").change(function () {

                var extension = $(this).val().split('.').pop().toLowerCase();
                var validFileExtensions = ['mp4', 'mov', 'wmv','WebM','MKV'];
                if ($.inArray(extension, validFileExtensions) == -1) {
                    alert("Sorry!! Upload only mp4, mov, wmv,WebM,MKV file");
                    $('#btnSubmit').prop('disabled', true);
                }
                else {
                    $('#btnSubmit').prop('disabled', false);
                }
            });

</script>
@endsection