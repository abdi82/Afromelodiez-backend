@extends('admin_layout.layout')
@section('content')

 

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('update_ad',$ad->id) }}" enctype='multipart/form-data' method="post">

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

                    <label for="CatgoryName">Advertisement type: </label>
                   <select name="type" id="Advertisementtype">
                       @if($ad->type =='image')
                           <option value="image" selected>Image</option>
                           <option value="video" >Video</option>
                       @else
                           <option value="video" selected>Video</option>
                           <option value="image" >Image</option>
  @endif

                   </select>
                </div>
                <div class="adfile">
                <label for="CatgoryName">Add file</label>
                <input type="file" name="attachment" id="attachment">
                </div>
                <div class="col-md-12 ">
                    <?php 
                
                if($ad->attachment != '')
                    { 
                    $extension= explode( '.', $ad->attachment);
                   if($extension[1] == 'png'|| $extension[1] == 'jpeg' || $extension[1] =='jpg' || $extension[1] == 'avif' || $extension[1] == 'webp'|| $extension[1] == 'svg'|| $extension[1] == 'gif' || $extension[1] == 'apng') { 
                ?>
                <img src="{{url('')}}/storage/ad/{{$ad->attachment}}" width="200" height="200">
                <?php }
//                        ($extension[1] == 'mp4' || $extension[1] =='mov' || $extension[1] =='wmv' || $extension[1] =='MKV' || $extension[1] =='WebM' || $extension[1] =='AVCHD' || $extension[1] ==' AVI' || $extension[1] == 'FLV')
                else
                {
                ?> 
             
                <video width="320" height="240" controls>
                  <source src="{{url('')}}/storage/ad/{{$ad->attachment}}" type="video/mp4">
                </video>

                <?php }
                } ?>
                </div>
                <div>
                <label for="CatgoryName"> Url </label>
                <input type="text" value="{{$ad->url}}" placeholder="Enter Url" name="url">
                </div>
<!--                   <div>
                  <label for="Location"> Location : </label>
                  <input type="radio" id="World" name="location_type" value="World" <?php echo ($ad->location_type == "World") ? 'checked' : ''; ?> >
                   <label for="World">World</label>
                  <input type="radio" id="Africa" name="location_type" value="Africa" <?php echo ($ad->location_type == "Africa") ? 'checked' : ''; ?> >
                  <label for="Africa">Africa</label>
                  <input type="radio" id="Asia" name="location_type" value="Asia" <?php echo ($ad->location_type == "Asia") ? 'checked' : ''; ?>  >
                  <label for="Asia">Asia</label>
                  <input type="radio" id="Europe" name="location_type" value="Europe"  <?php echo ($ad->location_type == "Europe") ? 'checked' : ''; ?> >
                  <label for="Europe">Europe</label>
                </div> -->
                <div>
                  <label for="Location"> Banner type select : </label>
                  <input type="radio" id="l" name="banner_type" value="l" <?php echo ($ad->banner_type == "l") ? 'checked' : ''; ?> >
                   <label for="l"> Home Screen</label>
                  <input type="radio" id="s" name="banner_type" value="s" <?php echo ($ad->banner_type == "s") ? 'checked' : ''; ?> >
                  <label for="s">Popup</label>
               </div>
               
                <input type="submit" value="Save" id="btnSubmit">
               <div> <h3> Click Bates: {{$sum}} </h3> </div>
            </form>
            

        </div>
    </div>
<script type="text/javascript">
$('.adfile').hide();
// $('select').on('change', function() {
//   $('#Advertisementtype').prop('disabled', true);
// });


//$('#Advertisementtype').prop('disabled', true);

       $('select').on('change', function() 
       { 
         $('#Advertisementtype').prop('disabled', true);
        $('.adfile').show();
       var adtype = $('#Advertisementtype').find(":selected").text();
        if(adtype == 'Image')
        {
            $(".adfile").change(function () {

                $('#Advertisementtype').prop('disabled', true);

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

        }
        else if(adtype == 'Video')
        {
               $(".adfile").change(function () {
                $('#Advertisementtype').prop('disabled', true);
                var extension = $("#attachment").val().split('.').pop().toLowerCase();
                var validFileExtensions = ['mp4', 'mov', 'wmv','WebM','MKV'];
                if ($.inArray(extension, validFileExtensions) == -1) {
                    alert("Sorry!! Upload only mp4, mov, wmv,WebM,MKV file");
                    $('#btnSubmit').prop('disabled', true);
                }
                else {
                    $('#btnSubmit').prop('disabled', false);
                }
            });
        }
       });
       $("#uploaded").submit(function (e) {
            
          $('#Advertisementtype').prop('disabled', false);
       });
</script>   
@endsection