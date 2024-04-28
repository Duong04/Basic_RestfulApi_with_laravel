@extends('layouts.admin')

@section('content')
<div class="container" style="width: 600px;">
    <h2>Create country</h2>
    <form action="" method="POST">
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
            <label for="country_code">Country code</label>
            <input type="text" class="form-control" id="country_code" name="country_code" placeholder="Enter country code" value="{{ old('country_code') }}">
        </div>
        <div class="form-group">
            <label for="country_name">Country name</label>
            <input type="text" class="form-control" id="country_name" name="country_name" placeholder="Enter country name" value="{{ old('country_name') }}">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
  </div>
@endsection