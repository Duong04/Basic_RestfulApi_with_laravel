<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h2>Send mail</h2>
        <form action="" method="POST">
            @if ($errors->any())
            <div class="alert alert-danger col-5">
                An error has occurred
            </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success col-5">
                    {{ session('success') }}
                </div>
            @endif
            @csrf
            <div class="mb-3 col-5">
                <label for="email" class="form-label">Email address</label>
                <input name="email" type="text" class="form-control" id="email" placeholder="Your email">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 col-5">
                <label for="message" class="form-label">Example textarea</label>
                <textarea name="message" class="form-control" id="message" rows="3" placeholder="Your message"></textarea>
                @error('message')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button class="btn btn-success">Submit</button>
        </form>
    </div>
</body>
</html>