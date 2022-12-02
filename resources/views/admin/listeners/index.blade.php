
@extends('admin_layout.layout')
@section('content')
<div class="row">
    <!-- column -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Users</h4>
                 @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    <a href="{{route('get_new_admin_form')}}"><button class="btn btn-info" style="float: right;">Add Manager </button></a>
                {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            
                                @forelse ($users as $key => $user)
                            <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                                
                                @empty
                                    
                                @endforelse
                                
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
     <div class="col-md-12">
        <?php if(count($users) > 1)
        {?>
         {{$users->links()}} <?php
        } ?>
     </div>
</div>
<script>
  $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var user_id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/changeStatus',
            data: {'status': status, 'user_id': user_id},
            success: function(data){
              console.log(data.success)
            }
        });
    })
  })
</script>
@endsection