@extends('admin_layout.layoutHome')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('update_featured') }}" enctype='multipart/form-data' method="post">

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
                <label for="CatgoryName">Name</label>

                <input type="text" value="{{$featured->name}}" placeholder="Enter Name" name="name">
                </div>
                <div>
                <label for="CatgoryName">Upload Image</label>
                <input type="file" value="{{$featured->image}}" placeholder="Upload Image" name="image">
                </div>
                <div>
                    <?php 
                
                if($featured->image != '')
                    { ?>
                <img src="{{url('')}}/storage/featured/{{$featured->image}}" width="200"  height="200">
                <?php } ?>
                </div> 
                <label name="search"> Add Song by search </label>
                    <select class="songsearch form-control p-3" name="song"></select>
                  <input type="hidden" value="{{$featured->id}}" name="id">
                <input type="submit" value="Save">
               
            </form>

        </div>
            </div>
        <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Song Name</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($song_data as $key => $song)
                                 <tr>
                                    <td>  {{$key + 1}} </td>
                                    <td>  {{$song->name}} </td>
                                    <td> <a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_episode_song',['id'=>$song->id ,'fid' =>$featured->id])}}"><i class="fa fa-trash"></i></a> </td>
                                    @empty

                                    @endforelse
                                     </tr> 
                            </tbody> 
                        </table>
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
                        $("input[name='search']").val(item.id);
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