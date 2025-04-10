@extends('layouts.app') 

@section('title', 'Home') 

@section('content')
    
<header class="d-flex justify-content-between align-items-center py-5">
    <div>
        <h1>Welcome to SpaceShare</h1>
        <p class="d-inline">Explore creative ideas and engage with the community.</p>
    </div>
    <a class="nav-link text-light hover-effect d-flex align-items-center" href="{{ url('/+') }}">
        <img src="{{ asset('/storage/images/') }}/add.jpg" alt="add icon" height="20" class="me-2">
        Add Idea
    </a>
</header>
    
<!-- Existing Ideas Section -->
<div class="row">
    <div class="col-md-20 mx-auto">
        <div class="list-group" id="ideas-container">
            <!-- Ideas will be loaded here via API -->
            <!-- Loading indicator (only shows while loading) -->
            <div id="loading-indicator" class="text-center p-4">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
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
    // Check if user is authenticated
    const isAuthenticated = () => {
        return localStorage.getItem('auth_token') !== null;
    };
    
    function showLoginModal() {
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    }
    
    function handleEdit(ideaId) {
        if (isAuthenticated()) {
            // If the user is logged in, redirect to the edit page
            window.location.href = `/edit-idea/${ideaId}`;
        } else {
            // If the user is not logged in, show the login/signup modal
            showLoginModal();
        }
    }
    
    function handleLike(ideaId, likeButton) {
        if (!isAuthenticated()) {
            showLoginModal();
            return;
        }
        
        const token = localStorage.getItem('auth_token');
        
        // Disable button during API call
        likeButton.disabled = true;
        
        fetch(`{{ url('/api/ideas') }}/${ideaId}/upvote`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update the like count on the button
            likeButton.innerHTML = `Like (${data.upvotes_count || 0})`;
            likeButton.disabled = false;
        })
        .catch(error => {
            console.error('Error liking idea:', error);
            likeButton.disabled = false;
        });
    }
    
    function handleComment(ideaId) {
        if (!isAuthenticated()) {
            showLoginModal();
            return;
        }
        
        // Redirect to the idea detail page
        window.location.href = `{{ url('/idea') }}/${ideaId}`;
    }
    
    // Function to create the HTML for an idea card
    function createIdeaCard(idea) {
        const likeButtonAction = isAuthenticated() 
            ? `handleLike(${idea.id}, this)` 
            : 'showLoginModal()';
            
        const commentButtonAction = isAuthenticated() 
            ? `handleComment(${idea.id})` 
            : 'showLoginModal()';
        
        return `
            <div class="list-group-item bg-dark text-light border-secondary p-3">
                <h5>
                    <a href="{{ url('/idea') }}/${idea.id}" class="text-light text-decoration-none">${idea.title}</a>
                </h5>
                <p>
                    ${idea.description}
                </p>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary" onclick="${likeButtonAction}">Like (${idea.upvotes_count || 0})</button>
                    <button class="btn btn-outline-secondary text-light" onclick="${commentButtonAction}">Comment (${idea.comments_count || 0})</button>
                </div>
            </div>
        `;
    }
    
    // Function to fetch ideas from API and display them
    function fetchIdeas() {
        const ideasContainer = document.getElementById('ideas-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        
        fetch('{{ url('/api/ideas') }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Remove loading indicator
                if (loadingIndicator) {
                    loadingIndicator.remove();
                }
                
                // Clear any existing content
                ideasContainer.innerHTML = '';
                
                // Check if data is an array or if it has a data property (for pagination)
                const ideas = Array.isArray(data) ? data : (data.data || []);
                
                if (ideas.length === 0) {
                    // No ideas found
                    ideasContainer.innerHTML = `
                        <div class="text-center p-5 text-light">
                            <p>No ideas found. Be the first to share your creative idea!</p>
                        </div>
                    `;
                    return;
                }
                
                // Add each idea to the container
                ideas.forEach(idea => {
                    ideasContainer.innerHTML += createIdeaCard(idea);
                });
            })
            .catch(error => {
                console.error('Error fetching ideas:', error);
                
                // Remove loading indicator
                if (loadingIndicator) {
                    loadingIndicator.remove();
                }
                
                // Show error message
                ideasContainer.innerHTML = `
                    <div class="text-center p-4 text-light">
                        <p>Failed to load ideas. Please try refreshing the page.</p>
                    </div>
                `;
            });
    }
    
    // Load ideas when the page loads
    document.addEventListener('DOMContentLoaded', fetchIdeas);
</script>

@endsection