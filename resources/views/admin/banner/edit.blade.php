@extends('admin_layout.layout')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('banner_store') }}" enctype='multipart/form-data' method="post">

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
                <div>
                <label for="CatgoryName">Add file</label>
                <input type="file" name="attachment[]" id="attachment" multiple="multiple">
                </div>

                <input type="submit" value="Save" id="btnSubmit">

            </form>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Banner Name</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($banner as $key => $value)
                                 <tr>
                                    <td>  {{$key + 1}} </td>
                                    <td>  <img src="{{url('')}}/storage/banner/{{$value->banner}}" width="200" height="200"> </td>
                                     
                                    <td> <a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_banner_image', $value->id )}}"><i class="fa fa-trash"></i></a> </td>
                                    @empty

                                    @endforelse
                                     </tr> 
                            </tbody> 
                        </table>
        </div>
        </div>
    </div>
<script type="text/javascript">

            $(".adfile").change(function () {

                var extension = $("#attachment").val().split('.').pop().toLowerCase();
                var validFileExtensions = ['png', 'jpeg', 'jpg', 'avif' ,'webp' ,'svg'];
                if ($.inArray(extension, validFileExtensions) == -1) {
                    alert("Sorry!! Upload only png, jpeg, jpg, avif, webp, svg file");
                    $('#btnSubmit').prop('disabled', true);
                }
                else {
                    $('#btnSubmit').prop('disabled', false);
                }
            });
</script>   
@endsection