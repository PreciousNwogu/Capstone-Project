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
    // Store user like statuses to track UI state
    const userLikedIdeas = {};
    
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
        
        // Toggle the liked state for immediate feedback
        const currentlyLiked = userLikedIdeas[ideaId] || false;
        userLikedIdeas[ideaId] = !currentlyLiked;
        
        // Update button appearance immediately for better UX
        updateLikeButton(ideaId, likeButton);
        
        fetch(`{{ url('/api/ideas') }}/${ideaId}/upvote`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to toggle like');
            }
            return response.json();
        })
        .then(data => {
            // Save like status to localStorage as fallback
            localStorage.setItem(`idea_${ideaId}_liked`, userLikedIdeas[ideaId]);
            
            // Update the like count with accurate count from server
            updateLikeCount(ideaId, data.upvotes_count || 0);
            
            // Re-enable button
            likeButton.disabled = false;
        })
        .catch(error => {
            console.error('Error toggling like:', error);
            
            // Revert the like status on error
            userLikedIdeas[ideaId] = currentlyLiked;
            updateLikeButton(ideaId, likeButton);
            
            // Re-enable button
            likeButton.disabled = false;
        });
    }
    
    function updateLikeButton(ideaId, button) {
        const likeCount = parseInt(button.getAttribute('data-likes') || 0);
        const isLiked = userLikedIdeas[ideaId] || false;
        
        if (isLiked) {
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-primary');
            button.innerHTML = `<i class="bi bi-heart-fill"></i> Liked (${likeCount})`;
        } else {
            button.classList.remove('btn-primary');
            button.classList.add('btn-outline-primary');
            button.innerHTML = `<i class="bi bi-heart"></i> Like (${likeCount})`;
        }
    }
    
    function updateLikeCount(ideaId, count) {
        const likeButtons = document.querySelectorAll(`[data-idea-id="${ideaId}"]`);
        likeButtons.forEach(button => {
            button.setAttribute('data-likes', count);
            const isLiked = userLikedIdeas[ideaId] || false;
            if (isLiked) {
                button.innerHTML = `<i class="bi bi-heart-fill"></i> Liked (${count})`;
            } else {
                button.innerHTML = `<i class="bi bi-heart"></i> Like (${count})`;
            }
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
    
    // Check if the user has liked an idea
    function checkUserLike(ideaId) {
        if (!isAuthenticated()) {
            return Promise.resolve(false);
        }
        
        const token = localStorage.getItem('auth_token');
        
        return fetch(`{{ url('/api/ideas') }}/${ideaId}/user-like`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    // API endpoint doesn't exist, fallback to local storage
                    return { liked: localStorage.getItem(`idea_${ideaId}_liked`) === 'true' };
                }
                throw new Error('Failed to check like status');
            }
            return response.json();
        })
        .then(data => {
            // Store the like status
            userLikedIdeas[ideaId] = data.liked || false;
            
            // Also store in localStorage as fallback
            localStorage.setItem(`idea_${ideaId}_liked`, userLikedIdeas[ideaId]);
            
            return userLikedIdeas[ideaId];
        })
        .catch(error => {
            console.error('Error checking like status:', error);
            // Fallback to localStorage
            userLikedIdeas[ideaId] = localStorage.getItem(`idea_${ideaId}_liked`) === 'true';
            return userLikedIdeas[ideaId];
        });
    }
    
    // Function to create the HTML for an idea card
    function createIdeaCard(idea) {
        const commentButtonAction = isAuthenticated() 
            ? `handleComment(${idea.id})` 
            : 'showLoginModal()';
        
        // Determine if user has liked this idea
        const hasLiked = userLikedIdeas[idea.id] || false;
        
        // Configure like button appearance based on like status
        const likeButtonClass = hasLiked ? 'btn-primary' : 'btn-outline-primary';
        const likeButtonIcon = hasLiked ? 'bi-heart-fill' : 'bi-heart';
        const likeButtonText = hasLiked ? 'Liked' : 'Like';
        
        return `
            <div class="list-group-item bg-dark text-light border-secondary p-3">
                <h5>
                    <a href="{{ url('/idea') }}/${idea.id}" class="text-light text-decoration-none">${idea.title}</a>
                </h5>
                <p>
                    ${idea.description}
                </p>
                <div class="d-flex justify-content-between">
                    <button class="btn ${likeButtonClass}" 
                            onclick="handleLike(${idea.id}, this)" 
                            data-idea-id="${idea.id}" 
                            data-likes="${idea.upvotes_count || 0}">
                        <i class="bi ${likeButtonIcon}"></i> ${likeButtonText} (${idea.upvotes_count || 0})
                    </button>
                    <button class="btn btn-outline-secondary text-light" onclick="${commentButtonAction}">
                        Comment (${idea.comments_count || 0})
                    </button>
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
                
                // Check like status for each idea if user is authenticated
                const likeChecks = isAuthenticated() 
                    ? Promise.all(ideas.map(idea => checkUserLike(idea.id)))
                    : Promise.resolve([]);
                
                likeChecks.then(() => {
                    // Add each idea to the container
                    ideas.forEach(idea => {
                        ideasContainer.innerHTML += createIdeaCard(idea);
                    });
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