
@extends('admin_layout.layout')
@section('content')
<div class="row">
    <!-- column -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Listing</h4>
                 
                <a href="{{route('add_notification')}}"><button class="btn btn-info" style="float: right;">Add Notification </button></a>
                {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                <div class="table-responsive">
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Message</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            
                                @forelse ($data as $value)
                            <tr>
                             <td>{{$value->id }}</td>
                            <td>{{$value->message}}</td>
                             <td><input type="checkbox" data-id="{{$value->id }}" name="status" class="js-switch"><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('delete_notification',$value->id)}}"><i class="fa fa-trash"></i></a>
                                <a class="btn btn-success del-cls" data-toggle="tooltip" data-placement="top" title="Edit" href="{{route('edit_notification',$value->id)}}"><i class="fas fa-pen"></i></a>
                              <a class="btn btn-success" data-toggle="tooltip" data-placement="top" title="Send" href="{{route('notification_reply',$value->id)}}">Send</a></td>
                                
                                @empty
                                    
                                @endforelse
                                
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
                 <div class="col-md-12">
        
     </div>
            </div>
        </div>
    </div>
    
</div>
@endsection