@extends('layouts.app') 

@section('title', 'Create Account') 

@section('content') 
<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card p-4 text-white" style="width: 350px;">
        <h3 class="text-center">Create Account</h3>
        <form id="registerForm" action="{{ url('/api/register') }}" method="POST">
            @csrf <!-- Include CSRF token for security -->
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                        <i id="eyeIconPassword" class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required>
                    <span class="input-group-text" onclick="toggleConfirmPassword()" style="cursor: pointer;">
                        <i id="eyeIconConfirmPassword" class="bi bi-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
        <div class="text-center my-3">
            <span class="text-light">Already have an account? </span>
            <a href="{{ url('/login') }}" class="text-decoration-underline text-danger custom-hover">Login</a>
        </div>
    </div>
</div>

<!-- Toggle Password Visibility Script -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIconPassword");

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

    function toggleConfirmPassword() {
        const confirmPasswordInput = document.getElementById("confirmPassword");
        const eyeIcon = document.getElementById("eyeIconConfirmPassword");

        if (confirmPasswordInput.type === "password") {
            confirmPasswordInput.type = "text";
            eyeIcon.classList.remove("bi-eye");
            eyeIcon.classList.add("bi-eye-slash");
        } else {
            confirmPasswordInput.type = "password";
            eyeIcon.classList.remove("bi-eye-slash");
            eyeIcon.classList.add("bi-eye");
        }
    }

    // Registration form submission handling
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form from submitting normally

        // Get form data
        const formData = new FormData(this);
        
        // Submit form data using AJAX (Fetch API)
        fetch('{{ url("/api/register") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Show success message
                alert(data.message);
                
                // Redirect to the URL provided by the server
                window.location.href = data.redirect_url;
            } else {
                // Handle error (show error message)
                if (data.errors) {
                    // Format validation errors
                    let errorMessage = 'Validation errors:\n';
                    for (const field in data.errors) {
                        errorMessage += `- ${data.errors[field].join('\n- ')}\n`;
                    }
                    alert(errorMessage);
                } else {
                    alert(data.message || 'Registration failed');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
</script>
@endsection