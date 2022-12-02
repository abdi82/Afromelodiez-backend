@extends('admin_layout.layoutHome')
@section('content')
    <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Album Listing</h4>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <a href="{{route('get_album_form_artist')}}"><button class="btn btn-info" style="float: right;">Add Album</button></a>
                    {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            @forelse ($album as $key => $album_data)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$album_data->name}}</td>
                                    <td><input type="checkbox" data-id="{{ $album_data->id }}" name="status" class="js-switch"><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_album_artist', $album_data->id)}}"><i class="fa fa-trash"></i></a><a class="btn btn-success del-cls" data-toggle="tooltip" data-placement="top" title="Edit" href="{{route('edit_album_artist', $album_data->id)}}"><i class="fas fa-pen"></i></a></td>

                                    @empty

                                    @endforelse

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <div class="col-md-12">
     {{$album->links()}}
        </div>
    </div>
    <script>
        $(function() {
            $('.toggle-class').change(function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var user_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '/changeStatus',
                    data: {'status': status, 'user_id': user_id},
                    success: function(data){
                        console.log(data.success)
                    }
                });
            })
        })
    </script>
@endsection