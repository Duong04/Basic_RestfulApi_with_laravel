<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="w-50 mx-auto border border-2 rounded p-3 mt-2">
            <h5>Đăng ký</h5>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <form action="{{route('register')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">First name</label>
                    <input name="first_name" type="text" class="form-control" id="exampleFormControlInput1" placeholder="First name" value="{{old('first_name')}}">
                    @error('first_name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Last name</label>
                    <input name="last_name" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Last name" value="{{old('last_name')}}">
                    @error('last_name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Date of birth</label>
                    <input name="dob" type="date" class="form-control" id="exampleFormControlInput1" value="{{old('dob')}}">
                    @error('dob')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Address</label>
                    <input name="address" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Your address" value="{{old('address')}}">
                    @error('address')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                    <input name="email" type="text" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{old('email')}}">
                    @error('email')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput2" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" id="exampleFormControlInput2" placeholder="Your password" value="{{old('password')}}">
                    @error('password')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput2" class="form-label">Password confirmation</label>
                    <input name="password_confirmation" type="password" class="form-control" id="exampleFormControlInput2" placeholder="Password confirmation" value="{{old('password_confirmation')}}">
                    @error('password_confirmation')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    Do you have an account?  
                    <a href="{{route('login')}}" class="text-decoration-none">Login</a>
                </div>
                <button class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</body>
</html>