@extends('admin_layout.layout')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section w-100">
            @if(empty($agreement))
            <form action="{{ route('add_agreement') }}" enctype='multipart/form-data' method="post">

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
                  <a target="_blank" href="{{ asset('/storage/agreement/Afromelodiez_License_Agreement.PDF')  }}"><i class="fas fa-download"></i> Click Here to Download form to sign Agreement </a>

                <div class="upload_agreement">
                    <label for="CatgoryName">Upload Agreement</label>
                    <input type="file"  name="agreement">
                  
                    <input type="submit" value="Save">
                </div>



            </form>
        </div>
    </div>
                @else
                <div class="row">
                    <!-- column -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Agreement</h4>
                                <div style="float: left;">
{{--                                    <form action="{{route('search_list_artist')}}">--}}
{{--                                        <label name="search"> Search Artist </label>--}}
{{--                                        <select class="artistsearch form-control p-3" name="search_artist"></select>--}}
{{--                                        <input type="submit" value="Search" class="btn btn-info">--}}
{{--                                    </form>--}}
                                </div>
                                @if(session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                <div class="table-responsive">
                    <table class="table agreement_tablr">
                        <thead>
                        <tr>

                            <th> Agreeemnt </th>
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            <tr>

                                <td><iframe src="{{asset('storage/artistagreement/'.$agreement->agreement)}}" title="W3Schools Free Online Web Tutorials"></iframe>
                                    </td>
                                <td><input type="checkbox" data-id="" name="status" class="js-switch"><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_agreement', $agreement->id)}}"><i class="fa fa-trash"></i></a></td>


                            </tr>

                        </tbody>
                    </table>
                </div>
                            </div>
                        </div>
                    </div>
                @endif


@endsection