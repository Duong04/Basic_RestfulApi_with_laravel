@extends('layouts.admin')

@section('content')
<div class="container" style="width: 600px;">
    <h2>Create classe</h2>
    <form action="{{ route('classe.add') }}" method="POST">
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
            <label for="name">Classe name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Classes name" value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label for="code">Classe code</label>
            <input type="text" class="form-control" id="code" name="code" placeholder="Enter classe code" value="{{ old('code') }}">
        </div>
        <div class="form-group">
            <label for="start_date">Start date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Enter start date" value="{{ old('start_date') }}">
        </div>
        <div class="form-group">
            <label for="end_date">End date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" placeholder="Enter end date" value="{{ old('end_date') }}">
        </div>
        <div class="form-group">
            <label for="Courses">Courses</label>
            <select class="form-control" name="course_id" id="Courses">
                <option value="">Courses</option>
                @foreach ($courses as $course)
                    <option value="{{ $course['id'] }}">{{ $course['course_name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="schedule">Schedule</label>
            <select class="form-control" name="schedule" id="schedule">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" id="description" placeholder="Description" cols="30" rows="5">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
@endsection