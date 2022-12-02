@extends('admin_layout.layoutHome')
@section('content')
                <div class="row">
                <!-- column -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                    <h4 class="card-title">Song Listing</h4> 
                    <div style="float: left;">
                    <form action="{{route('indexSearchArtist')}}"> 
                    <label name="search"> Search Song </label>
                    <select class="songsearch form-control p-3" name="search"></select>
                    <input type="submit" value="Search" class="btn btn-info">
                     </form>
                    </div>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <a href="{{route('artist_multiple_song_form')}}"><button class="btn btn-info" style="float: right;">Add Multiple Songs</button></a>
                    <a href="{{route('get_song_form')}}"><button class="btn btn-info" style="float: right;">Add Song</button></a>
                </div>
            </div>
        </div>
    </div>
                <div class="row">
                <!-- column -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                    {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th> Song Name</th>
                                <th> Song File Name </th>
                                <th> Artist </th>
                                <th> Language </th>
                                <th> Category </th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            
                                <tr>
                                    <td> 1 </td>
                                    <td>{{$song['name']}}</td>
                                    <td>{{$song['song']}}</td>
                                    <td>{{$song['artist_id']}}</td>
                                    <td>{{$song['category_id']}}</td>
                                    <td>{{$song['language_id']}}</td>

                                    <td><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_song', $song['id'])}}"><i class="fa fa-trash"></i></a><a class="btn btn-success del-cls" data-toggle="tooltip" data-placement="top" title="Edit" href="{{route('edit_song_artist', $song['id'])}}"><i class="fas fa-pen"></i></a></td>
                                     
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $('.songsearch').select2({
        placeholder: 'Search Song',
        ajax: {
            url: '/search-song-artist',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        $("input[name='search']").val(item.name);
                        console.log(item.id);
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