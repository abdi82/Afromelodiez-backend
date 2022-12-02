@extends('admin_layout.layout')
@section('content')

<div class="Catgory-section Catgory-sectionn">
        <div class="left-Catgory-section">
          <!-- <a href="{{route('get_notification') }}" class="btn btn-danger float-end">BACK</a> -->
           
<form action="{{route('update_notification',$data->id)}}" method="POST">
        @csrf
        @method('PUT')
         
               <div>
                    <label for="CatgoryName">Message:</label>
                       <input type="text" name="message" value="{{$data->message}}" class="form-control" placeholder="Put the Message">
                </div>
           <div class="col-md-12 Catgory-sectionn">
    <input type="submit" value="Update">
     </div>
   </div>
   </div>
    </form>

@endsection