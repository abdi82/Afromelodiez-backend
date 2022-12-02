@extends('admin_layout.layoutHome')
@section('content')

<div class="Catgory-section">
	<div class="left-Catgory-section">
     <?php $id= auth()->user()->id; ?>  
<form action="{{ route('multiple_song_store_artist',$id) }}" enctype='multipart/form-data' method="post">
    @if(session()->has('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
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
        <div class="col-md-12 ">
    <label for="CatgoryName"> Mp3 </label>
    <input type="file" name="multiplesong[]" multiple="multiple" id="Mysong">
    </div>
    <div class="col-md-12 ">
    <input type="submit" value="Save" id="btnSubmit">
     </div>               
</form>
</div>
</div>
<script>
 $("#Mysong").change(function () {

                var extension = $(this).val().split('.').pop().toLowerCase();

                var validFileExtensions = ['mp3', 'wav', 'mpeg'];
                if ($.inArray(extension, validFileExtensions) == -1) {
                    alert("Sorry!! Upload only mp3, mpeg, wav file");
                    $('#btnSubmit').prop('disabled', true);
                }
                else {
                    $('#btnSubmit').prop('disabled', false);
                }
            });
</script>
@endsection