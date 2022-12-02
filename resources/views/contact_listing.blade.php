
@extends('admin_layout.layout')
@section('content')
<div class="row">
    <!-- column -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Listing</h4>
                 <!-- @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif -->
                <!-- <a href="{{route('contact_listing')}}"><button class="btn btn-info" style="float: right;">Add Notification </button></a> -->

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                          
                                @forelse ($data as $value)
                            <tr>
                             <td>{{$value->id }}</td>
                            <td>{{$value->email}}</td>
                            <td>{{$value->message}}</td>
                             <td><input type="checkbox" data-id="{{$value->id }}" name="status" class="js-switch">
                                <a class="btn btn-success" title="Reply" href="{{route('reply-contact',$value->id)}}">Reply</a></td>

                             </td>

                                
                                @empty
                                    
                                @endforelse
                                
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
                 <div class="col-md-12">
        {{$data->links()}}
     </div>
            </div>
        </div>
    </div>
    
</div>
@endsection