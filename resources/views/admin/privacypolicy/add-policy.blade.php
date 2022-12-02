@extends('admin_layout.layout')
@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <title>Editor</title>
</head>
<body>
 <div class="row">
<div class="container">
    <form action="{{route('add-privacy-policy')}}" method="POST">
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
         <textarea name="description" id="description" rows="10" cols="80" placeholder="Put the short" >
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

@endsection