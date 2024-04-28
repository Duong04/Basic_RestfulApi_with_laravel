@extends('layouts.customer')
@section('content')
<article class="w-75">
    <div class="container">
        <h3 class="p-3">Các khóa học bạn đã đăng ký</h3>
        <div class="w-100">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Course name</th>
                        <th scope="col">Course code</th>
                        <th scope="col">Image</th>
                        <th scope="col">Class name</th>
                        <th scope="col">Class code</th>
                        <th scope="col">Start date</th>
                        <th scope="col">End date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $row)
                    <tr>
                        <th scope="row"><input type="checkbox" name="" id=""></th>
                        <td>{{ $row->course->course_name }}</td>
                        <td>{{ $row->course->course_code }}</td>
                        <td><img width="100px" height="70px" src="{{ asset('assets/courses/'.$row->course->course_image)}}" alt=""></td>
                        <td>{{ $row->classe->name }}</td>
                        <td>{{ $row->classe->code }}</td>
                        <td>{{ $row->classe->start_date }}</td>
                        <td>{{ $row->classe->end_date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</article>
@endsection