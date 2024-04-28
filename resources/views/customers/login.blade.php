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
        <div class="w-50 mx-auto border border-2 rounded p-3 mt-5">
            <h5>Login</h5>
            @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <form action="/login" method="POST">
                @csrf
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
                <a href="/forgot-password" class="d-block text-end text-decoration-none mb-3">Forgot password ?</a>
                <button class="btn btn-primary mb-3 w-100">Login</button>
                <div class="mb-3 text-center">
                    Don't have an account?  
                    <a href="{{route('register')}}" class="text-decoration-none">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>