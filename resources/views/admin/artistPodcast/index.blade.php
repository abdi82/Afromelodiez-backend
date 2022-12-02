@extends('admin_layout.layoutHome')
@section('content')
    <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Podcast Listing</h4>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <a href="{{route('episodeslist_artist')}}"><button class="btn btn-info" style="float: left;">Add Episodes </button></a>

                    <a href="{{route('get_podcast_create_form_artist')}}"><button class="btn btn-info" style="float: right;">Add Podast</button></a>
                    {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Podcast Name</th>
                                
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                             @forelse ($podcast as $key => $value)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$value->name}}</td>
                                    <td><input type="checkbox" data-id="{{ $value->id }}" name="status" class="js-switch"><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_podcast_artist', $value->id)}}"><i class="fa fa-trash"></i></a><a class="btn btn-success del-cls" data-toggle="tooltip" data-placement="top" title="Edit" href="{{route('edit_podcast_artist', $value->id)}}"><i class="fas fa-pen"></i></a></td>

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
     {{$podcast->links()}}
        </div>
    </div>

@endsection