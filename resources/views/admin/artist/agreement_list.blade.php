@extends('admin_layout.layout')
@section('content')
    <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Agreement Listing</h4>
                    <div style="float: left;">
                        <form action="{{route('search_list_artist')}}">
{{--                            <label name="search"> Search Artist </label>--}}
{{--                            <select class="artistsearch form-control p-3" name="search_artist"></select>--}}
{{--                            <input type="submit" value="Search" class="btn btn-info">--}}
                        </form>
                    </div>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th> Agreement </th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            @forelse ($agreement as $key => $agr)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$agr->user->email}}</td>
                                    <td><a target="_blank" href="{{asset('storage/artistagreement/'.$agr->agreement)}}"><i class="fas fa-download"></i></a></td>

                                    @empty
                                       <h1>No Data Found</h1>
                                    @endforelse

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            {{$agreement->links()}}
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