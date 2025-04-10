@extends('layouts.app') 

@section('title', 'Add Idea') 

@section('content') 
<div class="container my-4">
    <!-- Add Idea Form -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto"> 
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h5>Add a New Idea</h5>
                </div>
                <div class="card-body">
                    <!-- We'll show/hide this form with JavaScript based on token presence -->
                    <div id="ideaFormContainer" style="display: none;">
                        <form id="addIdeaForm">
                            @csrf <!-- Laravel CSRF token for security -->
                            <div class="mb-3">
                                <label for="ideaTitle" class="form-label">Idea Title</label>
                                <input type="text" class="form-control" id="ideaTitle" name="title" placeholder="Enter your idea title" required>
                            </div>
                            <div class="mb-3">
                                <label for="ideaDescription" class="form-label">Idea Description</label>
                                <textarea class="form-control" id="ideaDescription" name="description" rows="3" placeholder="Describe your idea" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Idea</button>
                        </form>
                    </div>
                    
                    <!-- Login message - will show/hide with JavaScript -->
                    <div id="loginMessage">
                        <p class="text-center">You need to be logged in to add a new idea.</p>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Login or Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Login/Signup Modal -->
<div class="modal" id="loginModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light"> 
            <div class="modal-header border-secondary"> 
                <h5 class="modal-title">Login or Sign Up</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <p>You need to be logged in to interact with ideas.</p>
                <!-- Redirect to actual login page -->
                <a href="{{ url('/login') }}" class="btn btn-primary w-100">Login</a>
                <!-- Redirect to actual sign up page -->
                <a href="{{ url('/createaccount') }}" class="btn btn-outline-primary w-100 mt-2">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in (has auth token)
    const token = localStorage.getItem('auth_token');
    const formContainer = document.getElementById('ideaFormContainer');
    const loginMessage = document.getElementById('loginMessage');
    
    // Show/hide elements based on authentication status
    if (token) {
        formContainer.style.display = 'block';
        loginMessage.style.display = 'none';
    } else {
        formContainer.style.display = 'none';
        loginMessage.style.display = 'block';
    }
    
    // Add event listener to form
    const ideaForm = document.getElementById('addIdeaForm');
    
    if (ideaForm) {
        ideaForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const token = localStorage.getItem('auth_token');
            if (!token) {
                alert('You must be logged in to submit an idea.');
                return;
            }
            
            // Create FormData object from the form
            const formData = new FormData(this);
            
            // Convert FormData to JSON
            const jsonData = {};
            formData.forEach((value, key) => {
                if (key !== '_token') { // Skip CSRF token
                    jsonData[key] = value;
                }
            });
            
            // Send API request
            fetch('{{ url("/api/ideas") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                alert('Your idea has been added successfully!');
                window.location.href = '{{ url("/") }}';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message || 'An error occurred. Please try again.');
            });
        });
    }
    
    // Automatically check token validity
    if (token) {
        // This is optional but recommended - verify the token is still valid
        fetch('{{ url("/api/ideas") }}', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                // Token is invalid or expired
                localStorage.removeItem('auth_token');
                formContainer.style.display = 'none';
                loginMessage.style.display = 'block';
                alert('Your session has expired. Please log in again.');
            }
        })
        .catch(error => {
            console.error('Token validation error:', error);
        });
    }
});
</script>

@endsection