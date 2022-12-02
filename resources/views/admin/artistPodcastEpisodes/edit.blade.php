@extends('admin_layout.layoutHome')
@section('content')

<div class="Catgory-section">
    <div class="left-Catgory-section">
<form action="{{ route('update_episode_artist',$episode->id) }}" enctype='multipart/form-data' method="post">
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
    <label for="CatgoryName">Episode Title </label>
    <input type="text" placeholder="Episode Title"name="title" value="{{$episode->title}}">
    </div>
    <div class="col-md-12">
        <label for="Lyrics">Description </label>
        <textarea id="Lyrics" name="description" rows="4" cols="50"> 
        {{$episode->description}}
        </textarea>
    </div>
        <div class="col-md-12 ">
    <label for="CatgoryName"> Mp3 </label>
    <input type="file" name="episode" value="{{$episode->episode}}">
    </div>
    <div class="col-md-12 ">
    <p> Mp3: {{$episode->episode}} </p>
    <audio controls>
  <source src="{{url('')}}/storage/podcast/episodes/{{$episode->episode}}" type="audio/ogg">
  </audio>
   </div>
        <div class="col-md-12 ">
            <label for="PodcastistName"> Select Podcast </label>
            <select class="podcastsearch form-control p-3" name="podcast"></select>
        </div>
      <div class="col-md-12 ">
        <p> Podcast : {{$podcast}} </p>
        </div>
    <div class="col-md-12 ">
    <label for="CatgoryName">Episode Image</label>
    <input type="file" name="image">
    </div> 
    <div class="col-md-12 ">
        <?php 
    
    if($episode->image != '')
        { ?>
    <img src="{{url('')}}/storage/podcast/episodes/images/{{$episode->image}}" width="200" height="200">
    <?php } ?>
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
    $('.podcastsearch').select2({
        placeholder: 'Select Podcast',
        ajax: {
            url: '/ajax-autocomplete-search-podcast',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        console.log(item.id);
                        $("input[name='podcast']").val(item.id);
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