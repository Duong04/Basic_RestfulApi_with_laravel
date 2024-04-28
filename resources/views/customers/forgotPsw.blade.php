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
            <h5>Forgot password</h5>
            @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <form action="/forgot-password" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                    <input name="email" type="text" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" value="{{old('email')}}">
                    @error('email')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <button class="btn btn-primary mb-3 w-100">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>