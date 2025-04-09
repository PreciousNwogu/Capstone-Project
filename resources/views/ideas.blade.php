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
                    @if(auth()->check())
                        <!-- Show the form if the user is logged in -->
                        <form action="{{ url('/add-idea') }}" method="POST">
                            @csrf <!-- Laravel CSRF token for security -->
                            <div class="mb-3">
                                <label for="ideaTitle" class="form-label">Idea Title</label>
                                <input type="text" class="form-control" id="ideaTitle" name="title" placeholder="Enter your idea title" required>
                            </div>
                            <div class="row">
                            <div class="col-md-6 mx-auto">
                                <div class="text-center mt-4">
                                    <a href="javascript:void(0);" class="btn btn-outline-primary" onclick="handleEdit(1)">Edit Idea</a>
                                </div>
                            </div>
                        </div>
                    </div>
                            <div class="mb-3">
                                <label for="ideaDescription" class="form-label">Idea Description</label>
                                <textarea class="form-control" id="ideaDescription" name="description" rows="3" placeholder="Describe your idea" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Idea</button>
                        </form>
                    @else
                        <!-- Show login/signup modal trigger if the user is not logged in -->
                        <p class="text-center">You need to be logged in to add a new idea.</p>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Login or Sign Up</button>
                    @endif
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


@endsection