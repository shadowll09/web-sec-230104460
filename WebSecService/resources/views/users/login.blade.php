@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{route('do_login')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="email" name="email" required>
      </div>
      <div class="form-group mb-2">
        <label for="model" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="password" name="password" required>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </form>
    
    <div class="text-center my-3">
      <p>OR</p>
      <div class="d-grid gap-2 mb-3">
        <a href="{{ route('login.google') }}" class="btn btn-danger">
          <i class="bi bi-google"></i> Login with Google
        </a>
        <a href="{{ route('login.linkedin') }}" class="btn btn-primary">
          <i class="bi bi-linkedin"></i> Login with LinkedIn
        </a>
      </div>
      <p class="mt-2 small text-muted">If you don't have an account, we'll create one for you.</p>
    </div>
    </div>
  </div>
</div>
@endsection
