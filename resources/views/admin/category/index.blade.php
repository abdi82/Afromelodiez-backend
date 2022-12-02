@extends('admin_layout.layout')
@section('content')
<div class="row">
    <!-- column -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Category Listing</h4>
                {{-- <h6 class="card-subtitle">Add class <code>.table</code></h6> --}}
                <a href="{{route('cat.page')}}" data-toggle="tooltip" data-placement="top center" title="Create New"><button class="btn btn-info" style="float: right;">Add Category</button></a>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @forelse ($catlist as $key => $cat)
                                 <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$cat->name}}</td>
                                
                                  <td><a href="{{route('cat.edit',$cat->id)}}" class="edit-cls btn btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a><a class="btn btn-danger del-cls" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure?')" href="{{route('cat.del', $cat->id)}}"><i class="fa fa-trash"></i></a></td>
                                
                                @empty
                                    
                                @endforelse
                                
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{$catlist->links()}}
</div>
@endsection