@extends('layouts.app') 

@section('title', 'Login')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 text-white" style="width: 350px;">
        <h3 class="text-center">Login</h3>
        <form action="{{ url('/api/login') }}" method="POST">
        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Email address</label>
                <input type="text" class="form-control" id="email address" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>
        <div class="text-center my-3">
            <span class="text-light">Not a member? </span>
            <a href="{{ url('/createaccount') }}" class="text-decoration-underline text-danger custom-hover">Sign up</a>
        </div>
    </div>
</div>
@endsection

