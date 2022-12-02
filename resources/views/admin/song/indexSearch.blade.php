@extends('admin_layout.layout')
@section('content')
                <div class="row">
                <!-- column -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                    <h4 class="card-title">Song Listing</h4> 
                    <div style="float: left;">
                    <form action="{{route('search_list')}}"> 
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

                    <a href="{{route('get_multiple_song_form')}}"><button class="btn btn-info" style="float: right;">Add Multiple Songs</button></a>
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
                                    <td>{{$song->name}}</td>
                                    <td>{{$song->song}}</td>
                                    <td>{{$song->artist_id}}</td>
                                    <td>{{$song->category_id}}</td>
                                    <td>{{$song->language_id}}</td>
                                    <td><input type="checkbox"  class="toggle-class" {{$song->status==0?'checked':''}} data-id="{{$song->id}}" data-toggle="toggle" data-size="lg"></td>
                                    <td><input type="checkbox" data-id="{{ $song->id }}" name="status" class="js-switch"><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_song', $song->id)}}"><i class="fa fa-trash"></i></a><a class="btn btn-success del-cls" data-toggle="tooltip" data-placement="top" title="Edit" href="{{route('edit_song', $song->id)}}"><i class="fa fa-pencil"></i></a></td>
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
            url: '/search-song',
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
    $('.toggle-class').change(function() {
        $('#message').empty();
        var status = $(this).prop('checked') == true ? 0 : 1;
        var song_id = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: '/song_status',
            data: {'status': status, 'song_id': song_id},
            success: function(data){
                // $('#message').html(' <div class="alert alert-success">'+data.message+ '</div>');
            }
        });
    })
</script>
@endsection 