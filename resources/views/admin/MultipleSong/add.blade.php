@extends('admin_layout.layout')
@section('content')

<div class="Catgory-section">
	<div class="left-Catgory-section">
<form action="{{ route('multiple_song_store') }}" enctype='multipart/form-data' method="post">
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
    <div id="audio"> </div> 
    </div>
    <input type="hidden" name="duration" id="duration">
    <div class="col-md-12 ">
    <input type="submit" value="Save" id="btnSubmit">
     </div>               
</form>
</div>
</div>
<script>

   var objectUrls= [];
   var allseconds=[];

 $("#Mysong").change(function (e) {
              //  getduration(this);
                              if (this.files) {
                    var i=1;

                    for (const file of this.files) {
                      var filename = file.name;
              
                      objectUrl = URL.createObjectURL(file);
                      objectUrls.push(objectUrl);
                   //$("#audio").prop("src", objectUrl);
                   $('#audio').append('<audio src="'+objectUrl+'" id="datasource'+i+'"/>');
                    //console.log(objectUrl);
                    //$("#audio")[0].src = '';
                    
                    i++;
                    }
                    console.log(objectUrls);
                    var j=1;
                    for(const urls of objectUrls)
                    {   
                        $("#datasource"+j).on("canplaythrough", function(e){
                            
                        //console.log(urls);
                        //  file=$('#datasource1');
                         var seconds = e.currentTarget.duration;
                            var duration = moment.duration(seconds, "seconds");
                            
                            var time = "";
                            var hours = duration.hours();
                            if (hours > 0) { time = hours + ":" ; }
                            
                            time = time + duration.minutes() + ":" + duration.seconds();
                              var parts = time.split(':'),
                                minutes = +parts[0],
                                seconds = +parts[1];
                            Seconds =(minutes * 60 + seconds).toFixed(3);
                            console.log('Test'+Seconds);
                            var result = Seconds.split('.');
                            allseconds.push(result[0]); 

                            console.log(allseconds);
                            $("#duration").val(allseconds); 
                        });
                        j++;

                    }
                }
             console.log('okay1');
         });

</script>
@endsection