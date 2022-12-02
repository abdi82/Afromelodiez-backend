@extends('admin_layout.layout')
@section('content')
    <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Artist Listing</h4>
                    <div style="float: left;">
                    <form action="{{route('search_list_artist')}}"> 
                    <label name="search"> Search Artist </label>
                    <select class="artistsearch form-control p-3" name="search_artist"></select>
                    <input type="submit" value="Search" class="btn btn-info">
                     </form>
                    </div>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <a href="{{route('get_artist_form')}}"><button class="btn btn-info" style="float: right;">Add Artist</button></a>
                    {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>location</th>
                                <th> Status </th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            @forelse ($artist as $key => $user)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->location}}</td>
                                    <td> <?php if ($user->isVerified == '1') { echo "Active"; } else { echo "InActive"; } ?> </td>
                                    <td><input type="checkbox" data-id="{{ $user->id }}" name="status" class="js-switch"><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_artist', $user->id)}}"><i class="fa fa-trash"></i></a><a class="btn btn-success del-cls" data-toggle="tooltip" data-placement="top" title="Edit" href="{{route('edit_artist', $user->id)}}"><i class="fas fa-pen"></i></a></td>

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
            {{$artist->links()}}
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $('.artistsearch').select2({
        placeholder: 'Search Artist',
        ajax: {
            url: '/search-artist',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        $("input[name='search_artist']").val(item.name);
                        console.log(item.name);
                        return {
                            text: item.name,
                            id: item.id,
                            value: item.name,
                        }
                    })
                };
            },
            cache: true
        }
    });
</script>
@endsection