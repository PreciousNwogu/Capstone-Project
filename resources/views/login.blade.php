@extends('layouts.app') 

@section('title', 'Login')

@section('content')
<!-- Add Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 text-white" style="width: 350px;">
        <h3 class="text-center">Login</h3>
        <form action="{{ url('/api/login') }}" method="POST">
            @csrf <!-- Include CSRF token for security -->
            
            <!-- Email Field -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>

            <!-- Password Field with Eye Icon -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                        <i id="eyeIcon" class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Remember me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>

        <!-- Signup Link -->
        <div class="text-center my-3">
            <span class="text-light">Not a member? </span>
            <a href="{{ url('/createaccount') }}" class="text-decoration-underline text-danger custom-hover">Sign up</a>
        </div>
    </div>
</div>

<!-- Toggle Password Visibility Script -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("bi-eye");
            eyeIcon.classList.add("bi-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("bi-eye-slash");
            eyeIcon.classList.add("bi-eye");
        }
    }
</script>

<!-- Success Notification -->
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            alert("{{ session('Login successful!') }}");
            window.location.href = "{{ url('/') }}"; // Redirect to home page
        });
    </script>
@endif
@endsection
