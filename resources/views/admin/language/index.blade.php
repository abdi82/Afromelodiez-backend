@extends('admin_layout.layout')
@section('content')
    <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Language Listing</h4>
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <a href="{{route('language_form')}}"><button class="btn btn-info" style="float: right;">Add Language</button></a>
                    {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Language</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                        <!--     @forelse ($language as $key => $languages)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$languages->name}}</td>
                                    <td><input type="checkbox" data-id="{{ $languages->id }}" name="status" class="js-switch"><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_language', $languages->id)}}"><i class="fa fa-trash"></i></a><a class="btn btn-success del-cls" data-toggle="tooltip" data-placement="top" title="Edit" href="{{route('edit_language', $languages->id)}}"><i class="fas fa-pen"></i></a></td>

                                    @empty

                                    @endforelse -->

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            {{$language->links()}}
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