@extends('layouts.admin')

@section('content')
<div class="container" style="width: 600px;">
    <h2>Create Course</h2>
    <form action="{{ route('course.add') }}" method="POST" enctype="multipart/form-data">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @csrf
        <div class="form-group">
            <label for="course_code">Course code</label>
            <input type="text" class="form-control" id="Course_code" name="course_code" placeholder="Enter course code" value="{{ old('course_code') }}">
        </div>
        <div class="form-group">
            <label for="course_name">Course name</label>
            <input type="text" class="form-control" id="Course name" name="course_name" placeholder="Enter course name" value="{{ old('course_name') }}">
        </div>
        <div class="form-group">
            <label for="course_image">Course image</label>
            <input type="file" class="form-control" id="Course_image" name="course_image" placeholder="Enter course image" value="{{ old('course_image') }}">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
@endsection