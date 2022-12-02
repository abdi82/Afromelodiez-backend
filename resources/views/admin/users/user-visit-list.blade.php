
@extends('admin_layout.layout')
@section('content')
<div class="row">
    <!-- column -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Visitors Listing</h4>
                 <!-- @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif -->
                <a href="#" class="btn btn-info" style="float: right;">Total Visitors: {{$users_visit}}</a>
                {{--<a href="{{route('get_new_admin_form')}}"><button class="btn btn-info" style="float: right;">Add Manager </button></a>--}}
                {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                 <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>

                               @forelse ($users_visit as $key => $value)
                            <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{@$value->users->name}}</td>
                            <td>{{@$value->users->email}}</td>
                            <td>{{@$value->users->user_role}}</td>
                            <td>
                              
                                
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