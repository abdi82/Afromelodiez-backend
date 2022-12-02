@extends('admin_layout.layout')
@section('content')

<div class="Catgory-section Catgory-sectionn">
        <div class="left-Catgory-section">
          
<form action="{{route('notification')}}" method="POST">
        @csrf
               <div>
                    <label for="CatgoryName">Message:</label>
                       <input type="text" name="message" class="form-control" placeholder="Put the Message">
     
                </div>
           <div class="col-md-12 Catgory-sectionn">
    <input type="submit" value="Save">
     </div>
   </div>
   </div>
    </form>

@endsection