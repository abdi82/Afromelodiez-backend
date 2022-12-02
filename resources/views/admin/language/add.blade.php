@extends('admin_layout.layout')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('add_language') }}" enctype='multipart/form-data' method="post">

                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label for="CatgoryName">Language</label>
                    <input type="text" placeholder="Enter Name"name="name">
                </div>

                <input type="submit" value="Save">

            </form>
        </div>
        <!-- <div class="left-Catgory-section">
            <a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Create New</a>
        </div> -->
    </div>

@endsection