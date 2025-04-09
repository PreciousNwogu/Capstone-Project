@extends('layouts.app') 

@section('title', 'Home') 

@section('content')
    
<header class="d-flex justify-content-between align-items-center py-5">
    <div>
        <h1>Welcome to SpaceShare</h1>
        <p class="d-inline">Explore creative ideas and engage with the community.</p>
    </div>
    <a class="nav-link text-light hover-effect d-flex align-items-center" href="{{ url('/add-idea') }}">
        <img src="{{ asset('/storage/images/') }}/add.jpg" alt="add icon" height="20" class="me-2">
        Add Idea
    </a>
</header>
    
<!-- Existing Ideas Section -->
<div class="row">
    <div class="col-md-20 mx-auto">
        <div class="list-group">
            <!-- Example Idea 1 -->
            <div class="list-group-item bg-dark text-light border-secondary p-3">
                <h5>
                    <a href="{{ url('/idea/1') }}" class="text-light text-decoration-none">Revolutionizing Renewable Energy</a>
                </h5>
                <p>
                    A groundbreaking idea to harness solar and wind energy more efficiently using AI-driven optimization algorithms. 
                    This could significantly reduce energy costs and carbon emissions worldwide.
                </p>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary" onclick="showLoginModal()">Like (45)</button>
                    <button class="btn btn-outline-secondary text-light" onclick="showLoginModal()">Comment (12)</button>
                </div>
            </div>

            <!-- Example Idea 2 -->
            <div class="list-group-item bg-dark text-light border-secondary p-3">
                <h5>
                    <a href="{{ url('/idea/2') }}" class="text-light text-decoration-none">Smart Agriculture with IoT</a>
                </h5>
                <p>
                    Leveraging IoT devices to monitor soil health, weather conditions, and crop growth in real-time. 
                    This idea aims to help farmers increase yield and reduce resource wastage.
                </p>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary" onclick="showLoginModal()">Like (32)</button>
                    <button class="btn btn-outline-secondary text-light" onclick="showLoginModal()">Comment (8)</button>
                </div>
            </div>

            <!-- Example Idea 3 -->
            <div class="list-group-item bg-dark text-light border-secondary p-3">
                <h5>
                    <a href="{{ url('/idea/3') }}" class="text-light text-decoration-none">AI-Powered Personal Health Assistant</a>
                </h5>
                <p>
                    An AI-driven app that tracks your daily activities, diet, and exercise routines to provide personalized health recommendations. 
                    It could also integrate with wearable devices for better insights.
                </p>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary" onclick="showLoginModal()">Like (58)</button>
                    <button class="btn btn-outline-secondary text-light" onclick="showLoginModal()">Comment (20)</button>
                </div>
            </div>

            <!-- Example Idea 4 -->
            <div class="list-group-item bg-dark text-light border-secondary p-3">
                <h5>
                    <a href="{{ url('/idea/4') }}" class="text-light text-decoration-none">Virtual Reality for Education</a>
                </h5>
                <p>
                    Using VR technology to create immersive learning experiences for students. 
                    Imagine exploring ancient civilizations or conducting virtual science experiments from your classroom.
                </p>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary" onclick="showLoginModal()">Like (74)</button>
                    <button class="btn btn-outline-secondary text-light" onclick="showLoginModal()">Comment (15)</button>
                </div>
            </div>

            <!-- Example Idea 5 -->
            <div class="list-group-item bg-dark text-light border-secondary p-3">
                <h5>
                    <a href="{{ url('/idea/5') }}" class="text-light text-decoration-none">Eco-Friendly Packaging Solutions</a>
                </h5>
                <p>
                    Developing biodegradable and reusable packaging materials to replace single-use plastics. 
                    This idea could help reduce plastic pollution and promote sustainable practices.
                </p>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary" onclick="showLoginModal()">Like (39)</button>
                    <button class="btn btn-outline-secondary text-light" onclick="showLoginModal()">Comment (10)</button>
                </div>
            </div>
        </div>
    </div>
</div>
    
<!-- Modal for login/sign up -->
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
    function handleEdit(ideaId) {
        @if(auth()->check())
            // If the user is logged in, redirect to the edit page
            window.location.href = `/edit-idea/${ideaId}`;
        @else
            // If the user is not logged in, show the login/signup modal
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        @endif
    }

    function showLoginModal() {
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    }
</script>

@endsection