@extends('layouts.app') 

@section('title', 'Create Account') 

@section('content') 
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 text-white" style="width: 350px;">
        <h3 class="text-center">Create Account</h3>
        <form action="{{ url('/api/register') }}" method="POST">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name= "email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
        <div class="text-center my-3">
            <span class="text-light">Already have an account? </span>
            <a href="{{ url('/login') }}" class="text-decoration-underline text-danger custom-hover">Login</a>
        </div>
    </div>
</div>
@endsection

