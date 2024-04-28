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
            <h5>Reset password</h5>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
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
                <button class="btn btn-primary mb-3 w-100">Reset</button>
            </form>
        </div>
    </div>
</body>
</html>