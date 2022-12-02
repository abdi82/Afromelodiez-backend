@extends('admin_layout.layout')
@section('content')

    <div class="Catgory-section">
        <div class="left-Catgory-section">
            <form action="{{ route('add_album') }}" enctype='multipart/form-data' method="post">

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
                    <label for="CatgoryName">Name</label>
                    <input type="text" placeholder="Enter Name" name="name">
                </div>
                <div>
                    <label for="CatgoryName">Upload Album Image</label>
                    <input type="file" placeholder="Upload Album Image" name="image">
                </div>
                <input type="submit" value="Save">

            </form>
        </div>
    </div>

@endsection