
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <title>Editor</title>
</head>
<body>
 <div class="row">
<div class="container">
    <form action="{{route('storeadmin')}}" method="POST">
        @csrf

        <div class="row">
        <form name="ckeditor">

                 <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Title:</strong>
            <input type="text" name="title" class="form-control" placeholder="Put the Title">
                </div>
            </div>

                   <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Short Description:</strong>
                    <input type="text" name="short_description" class="form-control" placeholder="Put the short">
    </div>

            <lable>Discription</lable>
         <textarea name="description" id="description" rows="10" cols="80">
            This is my textarea to be replaced with CKEditor.
        </textarea>
       
     
        
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
   </div>
    </form>
                 <script>
                             ClassicEditor
                                .create( document.querySelector( '#description' ) )
                                .then( editor => {
                                        console.log( description );
                                } )
                                .catch( error => {
                                        console.error( error );
                                } );
                        
                </script>
</body>
</html>