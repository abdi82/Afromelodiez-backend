@extends('admin_layout.layout')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('add_ad') }}" enctype='multipart/form-data' method="post" id="uploaded">

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
                    <option disabled="disabled" selected>None </option>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                   </select>
                </div>
                <div class="adfile">    
                    <label for="CatgoryName"> Add file </label>
                    <input type="file" name="attachment" id="attachment">
                </div>
                <div>
                    <label for="CatgoryName">Enter AD link</label>
                    <input type="text" placeholder="Enter Url" name="url">
                </div>
<!--                 <div>
                  <label for="Location"> Location : </label>
                  <input type="radio" id="World" name="location_type" value="World">
                   <label for="World">World</label>
                  <input type="radio" id="Africa" name="location_type" value="Africa">
                  <label for="Africa">Africa</label>
                  <input type="radio" id="Asia" name="location_type" value="Asia">
                  <label for="Asia">Asia</label>
                  <input type="radio" id="Europe" name="location_type" value="Europe">
                  <label for="Europe">Europe</label>
                </div> -->
                <div>
                  <label for="Location"> Banner type select : </label>
                  <input type="radio" id="l" name="banner_type" value="l">
                   <label for="l"> Home Screen</label>
                  <input type="radio" id="s" name="banner_type" value="s">
                  <label for="s">Popup </label>
               </div>
                <input type="submit" value="Save" id="btnSubmit">

            </form>
        </div>
    </div>

<script type="text/javascript">
        $('.adfile').hide();
        $('#locationType').on('change',function()
       {
          var type = $('#locationType').val();
          console.log(type);
          if(type == 'Continent')
          {
             $('#ContinentMultiple').css('display','block');
          }

       });

       $('select').on('change', function() 
       { 
         $('#Advertisementtype').prop('disabled', true);
        $('.adfile').show();
       var adtype = $('#Advertisementtype').find(":selected").text();
        if(adtype == 'Image')
        {    
          console.log(adtype);
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
        else
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