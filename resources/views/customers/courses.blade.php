@extends('layouts.customer')
@section('content')
<article>
    <div class="container">
        <h3 class="p-3">Các khóa học hiện có</h3>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="d-flex flex-wrap mx-auto" style="gap: 20px; width: 90%;">
            @php
                $i = 0;
            @endphp
            @foreach ($courses as $row)
            @php
                $i++;    
            @endphp
                <form action="{{ route('courses.register') }}" method="POST">
                    @csrf
                    <div class="modal fade" id="exampleModal-{{$i}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title {{$i}}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="classe" class="py-2">Choose your class</label>
                                    <select name="class_id" id="classe" class="form-control">
                                        @foreach ($row->classes as $classe)
                                            <option value="{{$classe->id}}">{{$classe->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input name="course_id" type="hidden" value="{{$row['id']}}">
                    <div class="card" style="width: 18rem;">
                        <img width="100%" height="250px" class="object-fit-cover" src="{{ asset('assets/courses/'.$row['course_image']) }}" alt="">
                        <div class="card-body">
                            <h5 class="card-title">{{ $row['course_name'] }}</h5>
                            <div class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal-{{$i}}">Đăng ký</div>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
</article>
@endsection