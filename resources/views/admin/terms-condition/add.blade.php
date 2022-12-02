@extends('admin_layout.layout')
@section('content')
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>

<div class="Catgory-section Catgory-sectionn">
        <div class="left-Catgory-section">
      <form action="{{route('edit-terms_conditions')}}" name="ckeditor" method="POST">
        @csrf

                <div>
                    <label for="CatgoryName">Title:</label>
                     <input type="text" name="title" value ="{{$data->title}}" class="form-control" placeholder="Put the Title">
                </div>

                <div>
                    <label for="CatgoryName">Short Description:</label>
                      <input type="text" name="short_description" value="{{$data->short_description}}" class="form-control" placeholder="Put the short">
                </div>


                <div class="text-editor-ck-discription">
                    <label for="CatgoryName">Discription:</label>
                       <textarea name="description" id="description" rows="10" cols="80">
           {{$data->description}}
        </textarea>
                </div>

        
           <div class="col-md-12 Catgory-sectionn">
    <input type="submit" value="Save">
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