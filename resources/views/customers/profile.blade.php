@extends('layouts.customer')

@section('content')
    <article>
        <div class="container p-5">
            <div class="mx-auto border bg-white p-5 rounded" style="width: 900px;">
                <h5 class="pb-4">Your profile</h5>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form action="/profile" method="POST" class="d-flex justify-content-between" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="col-3 position-relative d-flex align-items-center flex-column border-end">
                        <img src="{{ asset('assets/avatar/' . $user['avatar']) }}" width="130px" height="130px" class="rounded-circle object-fit-cover" alt="">
                        <label for="image" class="position-absolute d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border-radius: 50%; background-color: #ccc; right: 35px; top: 100px; cursor: pointer;">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input name="avatar" hidden type="file" id="image">
                        <h6 class="mt-3">{{$user['name']}}</h6>
                    </div>
                    <div class="col-9 px-5">
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="first-name" class="form-label">First name</label>
                                <input name="first_name" type="text" id="first-name" class="form-control" placeholder="First name" value="{{$user['first_name']}}">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="last-name" class="form-label">Last name</label>
                                <input name="last_name" type="text" id="last-name" class="form-control" placeholder="Last name" value="{{$user['last_name']}}">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="email" class="form-label">Email</label>
                                <input disabled type="text" id="email" class="form-control" placeholder="Your email" value="{{$user['email']}}">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input name="phone" type="number" id="phone" class="form-control" placeholder="Your phone" value="{{$user['phone']}}">
                            </div>
                            <div class="mb-3 col-6">
                                <label for="address" class="form-label">Address</label>
                                <input name="address" type="text" id="address" class="form-control" placeholder="Your Address" value="{{$user['address']}}">
                            </div>
                            <div class="mb-3 col-12">
                                <button class="btn btn-primary">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </article>
@endsection