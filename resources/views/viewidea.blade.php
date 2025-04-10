@extends('layouts.app')

@section('title', 'Idea Details')

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div id="loading-indicator" class="text-center p-5">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-light">Loading idea...</p>
            </div>

            <div id="idea-container" style="display: none;">
                <!-- Idea Details -->
                <div class="card bg-dark text-light">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0" id="idea-title"></h3>
                        <div class="d-flex gap-2" id="idea-actions">
                            <!-- Edit Button -->
                            <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm" id="edit-idea-btn">Edit</a>

                            <!-- Delete Button -->
                            <button type="button" class="btn btn-outline-danger btn-sm" id="delete-idea-btn">Delete</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p id="idea-description"></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary" id="idea-likes">Likes: 0</span>
                            <button class="btn btn-outline-primary" id="like-idea-btn">Like</button>
                        </div>
                    </div>
                </div>

                <!-- Edit Idea Form (Initially Hidden) -->
                <div id="edit-form-container" class="card bg-dark text-light mt-4" style="display: none;">
                    <div class="card-header">
                        <h5>Edit Idea</h5>
                    </div>
                    <div class="card-body">
                        <form id="edit-idea-form">
                            <div class="mb-3">
                                <label for="edit-title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="edit-title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit-description" name="description" rows="5" required></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="cancel-edit-btn">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card bg-dark text-light mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Comments</h5>
                        <button class="btn btn-outline-light btn-sm" id="refresh-comments-btn">Refresh</button>
                    </div>
                    <div class="card-body">
                        <ul class="list-group" id="comments-list">
                            <!-- Comments will be loaded here -->
                        </ul>
                        <p id="no-comments-message" class="text-center" style="display: none;">No comments yet.</p>
                    </div>
                </div>

                <!-- Add Comment Section -->
                <div class="card bg-dark text-light mt-4">
                    <div class="card-header">
                        <h5>Add a Comment</h5>
                    </div>
                    <div class="card-body">
                        <div id="comment-form-container">
                            <form id="comment-form">
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Your Comment</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Comment</button>
                            </form>
                        </div>
                        <div id="login-to-comment" style="display: none;">
                            <p class="text-center">You need to be logged in to submit a comment.</p>
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Login or Sign Up</button>
                        </div>
                    </div>
                </div>

                <!-- View All Comments Button
                <div class="text-center mt-4">
                    <button id="view-all-comments-btn" class="btn btn-outline-info">View All Comments</button>
                </div> -->

                <!-- Back to Home Button -->
                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="btn btn-outline-light">Back to Home</a>
                </div>
            </div>

            <!-- All Comments Container (Initially Hidden) -->
            <!-- <div id="all-comments-container" class="card bg-dark text-light mt-4" style="display: none;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Comments</h5>
                    <button class="btn btn-outline-light btn-sm" id="back-to-idea-btn">Back to Idea</button>
                </div>
                <div class="card-body">
                    <div id="comments-loading" class="text-center p-3">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Loading comments...</span>
                        </div>
                        <p class="mt-2">Loading comments...</p>
                    </div>
                    <ul class="list-group" id="all-comments-list">
                        All comments will be loaded here -->
                    <!-- </ul>
                    <p id="no-all-comments-message" class="text-center" style="display: none;">No comments available.</p>
                </div>
            </div> --> 

            <!-- Error message container -->
            <div id="error-container" class="alert alert-danger" style="display: none;">
                Idea not found or an error occurred while loading the idea.
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
                <a href="{{ url('/login') }}" class="btn btn-primary w-100">Login</a>
                <a href="{{ url('/createaccount') }}" class="btn btn-outline-primary w-100 mt-2">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this idea? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Get the idea ID from the URL
    const ideaId = window.location.pathname.split('/').pop();
    let ideaData = null;
    
    // Check if user is authenticated
    const isAuthenticated = () => {
        return localStorage.getItem('auth_token') !== null;
    };
    
    // Show login modal
    function showLoginModal() {
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
    }
    
    // Handle edit button click
    function handleEdit(ideaId) {
        if (!isAuthenticated()) {
            showLoginModal();
            return;
        }
        
        // Show the edit form and hide the idea details
        const ideaDetails = document.querySelector('.card.bg-dark.text-light');
        const editFormContainer = document.getElementById('edit-form-container');
        
        // Fill the form with existing data
        document.getElementById('edit-title').value = ideaData.title;
        document.getElementById('edit-description').value = ideaData.description;
        
        // Show edit form and hide idea details
        ideaDetails.style.display = 'none';
        editFormContainer.style.display = 'block';
    }
    
    // Handle cancel edit button
    function handleCancelEdit() {
        const ideaDetails = document.querySelector('.card.bg-dark.text-light');
        const editFormContainer = document.getElementById('edit-form-container');
        
        // Hide edit form and show idea details
        editFormContainer.style.display = 'none';
        ideaDetails.style.display = 'block';
    }
    
    // Handle edit form submission
    function setupEditForm() {
        const editForm = document.getElementById('edit-idea-form');
        const cancelEditBtn = document.getElementById('cancel-edit-btn');
        
        // Set up cancel button
        cancelEditBtn.addEventListener('click', handleCancelEdit);
        
        // Set up form submission
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!isAuthenticated()) {
                showLoginModal();
                return;
            }
            
            const token = localStorage.getItem('auth_token');
            const formData = {
                title: document.getElementById('edit-title').value,
                description: document.getElementById('edit-description').value
            };
            
            fetch(`{{ url('/api/ideas') }}/${ideaId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update idea');
                }
                return response.json();
            })
            .then(data => {
                // Update the idea data
                ideaData = data;
                
                // Update the displayed idea details
                document.getElementById('idea-title').textContent = data.title;
                document.getElementById('idea-description').textContent = data.description;
                
                // Hide the edit form and show the idea details
                handleCancelEdit();
                
                // Show success message
                alert('Idea updated successfully');
            })
            .catch(error => {
                console.error('Error updating idea:', error);
                alert('Failed to update idea. Please try again.');
            });
        });
    }
    
    // Load idea data from API
    function loadIdea() {
        const loadingIndicator = document.getElementById('loading-indicator');
        const ideaContainer = document.getElementById('idea-container');
        const errorContainer = document.getElementById('error-container');
        
        fetch(`{{ url('/api/ideas') }}/${ideaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Idea not found');
                }
                return response.json();
            })
            .then(data => {
                // Store the idea data
                ideaData = data;
                
                // Update the page title
                document.title = data.title;
                
                // Fill in the idea details
                document.getElementById('idea-title').textContent = data.title;
                document.getElementById('idea-description').textContent = data.description;
                document.getElementById('idea-likes').textContent = `Likes: ${data.upvotes_count || 0}`;
                
                // Set up the edit button
                const editButton = document.getElementById('edit-idea-btn');
                editButton.onclick = () => handleEdit(data.id);
                
                // Set up the delete button
                const deleteButton = document.getElementById('delete-idea-btn');
                deleteButton.onclick = () => showDeleteConfirmation(data.id);
                
                // Show or hide action buttons based on authentication
                const actionsContainer = document.getElementById('idea-actions');
                actionsContainer.style.display = isAuthenticated() ? 'flex' : 'none';
                
                // Set up the like button
                const likeButton = document.getElementById('like-idea-btn');
                likeButton.onclick = () => handleLike(data.id);
                
                // Load comments
                loadComments(data.id);
                
                // Show or hide comment form based on authentication
                const commentFormContainer = document.getElementById('comment-form-container');
                const loginToComment = document.getElementById('login-to-comment');
                
                if (isAuthenticated()) {
                    commentFormContainer.style.display = 'block';
                    loginToComment.style.display = 'none';
                } else {
                    commentFormContainer.style.display = 'none';
                    loginToComment.style.display = 'block';
                }
                
                // Hide loading indicator and show idea
                loadingIndicator.style.display = 'none';
                ideaContainer.style.display = 'block';
            })
            .catch(error => {
                console.error('Error loading idea:', error);
                loadingIndicator.style.display = 'none';
                errorContainer.style.display = 'block';
            });
    }
    
    // Load comments for the idea
    function loadComments(ideaId) {
        fetch(`{{ url('/api/ideas') }}/${ideaId}/comments`)
            .then(response => response.json())
            .then(data => {
                const commentsList = document.getElementById('comments-list');
                const noCommentsMessage = document.getElementById('no-comments-message');
                
                // Check if data is an array or has a data property
                const comments = Array.isArray(data) ? data : (data.data || []);
                
                if (comments.length === 0) {
                    commentsList.innerHTML = '';
                    noCommentsMessage.style.display = 'block';
                    return;
                }
                
                noCommentsMessage.style.display = 'none';
                commentsList.innerHTML = '';
                
                comments.forEach(comment => {
                    const commentItem = document.createElement('li');
                    commentItem.className = 'list-group-item bg-dark text-light border-secondary';
                    
                    // Get the author information
                    const authorName = comment.user ? comment.user.name : 'Anonymous';
                    
                    commentItem.innerHTML = `
                        <strong>${authorName}:</strong>
                        <p>${comment.content || comment.body}</p>
                    `;
                    
                    // Add delete button if user is authenticated
                    if (isAuthenticated()) {
                        const deleteButton = document.createElement('button');
                        deleteButton.className = 'btn btn-sm btn-outline-danger float-end';
                        deleteButton.textContent = 'Delete';
                        deleteButton.onclick = () => handleDeleteComment(comment.id);
                        commentItem.appendChild(deleteButton);
                    }
                    
                    commentsList.appendChild(commentItem);
                });
            })
            .catch(error => {
                console.error('Error loading comments:', error);
                const commentsList = document.getElementById('comments-list');
                const noCommentsMessage = document.getElementById('no-comments-message');
                
                commentsList.innerHTML = '';
                noCommentsMessage.textContent = 'Failed to load comments.';
                noCommentsMessage.style.display = 'block';
            });
    }
    
    // Fetch all comments from API
    function fetchAllComments() {
        const commentsLoading = document.getElementById('comments-loading');
        const allCommentsList = document.getElementById('all-comments-list');
        const noAllCommentsMessage = document.getElementById('no-all-comments-message');
        
        // Show loading indicator
        commentsLoading.style.display = 'block';
        allCommentsList.innerHTML = '';
        noAllCommentsMessage.style.display = 'none';
        
        fetch(`{{ url('/api/comments') }}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load comments');
                }
                return response.json();
            })
            .then(data => {
                // Hide loading indicator
                commentsLoading.style.display = 'none';
                
                // Check if data is an array or has a data property
                const comments = Array.isArray(data) ? data : (data.data || []);
                
                if (comments.length === 0) {
                    allCommentsList.innerHTML = '';
                    noAllCommentsMessage.style.display = 'block';
                    return;
                }
                
                noAllCommentsMessage.style.display = 'none';
                allCommentsList.innerHTML = '';
                
                comments.forEach(comment => {
                    const commentItem = document.createElement('li');
                    commentItem.className = 'list-group-item bg-dark text-light border-secondary';
                    
                    // Get the author information
                    const authorName = comment.user ? comment.user.name : 'Anonymous';
                    
                    // Create comment content with idea link
                    commentItem.innerHTML = `
                        <strong>${authorName}:</strong>
                        <p>${comment.content || comment.body}</p>
                        <small>Idea: <a href="/ideas/${comment.idea_id}" class="text-primary">${comment.idea ? comment.idea.title : 'Unknown'}</a></small>
                    `;
                    
                    // Add delete button if user is authenticated
                    if (isAuthenticated()) {
                        const deleteButton = document.createElement('button');
                        deleteButton.className = 'btn btn-sm btn-outline-danger float-end';
                        deleteButton.textContent = 'Delete';
                        deleteButton.onclick = () => handleDeleteComment(comment.id, true);
                        commentItem.appendChild(deleteButton);
                    }
                    
                    allCommentsList.appendChild(commentItem);
                });
            })
            .catch(error => {
                console.error('Error fetching all comments:', error);
                commentsLoading.style.display = 'none';
                noAllCommentsMessage.textContent = 'Failed to load comments.';
                noAllCommentsMessage.style.display = 'block';
            });
    }
    
    // Handle like button click
    function handleLike(ideaId) {
        if (!isAuthenticated()) {
            showLoginModal();
            return;
        }
        
        const token = localStorage.getItem('auth_token');
        
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
            // Update the likes count
            document.getElementById('idea-likes').textContent = `Likes: ${data.upvotes_count || 0}`;
        })
        .catch(error => {
            console.error('Error liking idea:', error);
            alert('Failed to like the idea. Please try again.');
        });
    }
    
    // Handle comment form submission
    function setupCommentForm() {
        const commentForm = document.getElementById('comment-form');
        const commentInput = document.getElementById('comment');
        
        if (commentForm) {
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!isAuthenticated()) {
                    showLoginModal();
                    return;
                }
                
                // Get token and comment text
                const token = localStorage.getItem('auth_token');
                const commentText = commentInput.value.trim();
                
                // Validate comment
                if (!commentText) {
                    alert('Please enter a comment');
                    return;
                }
                
                // Show loading state
                const submitButton = commentForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.textContent = 'Submitting...';
                
                // Send API request
                fetch(`{{ url('/api/ideas') }}/${ideaId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: commentText })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to submit comment');
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear the form
                    commentInput.value = '';
                    
                    // Add the new comment to the list without reloading
                    addCommentToList(data);
                    
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                })
                .catch(error => {
                    console.error('Error submitting comment:', error);
                    alert('Failed to submit comment. Please try again.');
                    
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                });
            });
        }
    }
    
    // Add a new comment to the comments list
    function addCommentToList(comment) {
        const commentsList = document.getElementById('comments-list');
        const noCommentsMessage = document.getElementById('no-comments-message');
        
        // Hide the "no comments" message
        noCommentsMessage.style.display = 'none';
        
        // Create a new comment element
        const commentItem = document.createElement('li');
        commentItem.className = 'list-group-item bg-dark text-light border-secondary';
        
        // Get the author information
        const authorName = comment.user ? comment.user.name : 'Anonymous';
        
        // Build the comment HTML
        commentItem.innerHTML = `
            <strong>${authorName}:</strong>
            <p>${comment.content || comment.body}</p>
        `;
        
        // Add delete button
        if (isAuthenticated()) {
            const deleteButton = document.createElement('button');
            deleteButton.className = 'btn btn-sm btn-outline-danger float-end';
            deleteButton.textContent = 'Delete';
            deleteButton.onclick = () => handleDeleteComment(comment.id);
            commentItem.appendChild(deleteButton);
        }
        
        // Add to the beginning of the list (newest first)
        commentsList.insertBefore(commentItem, commentsList.firstChild);
    }
    
    // Handle delete comment
    function handleDeleteComment(commentId, isAllCommentsView = false) {
        if (!isAuthenticated()) {
            showLoginModal();
            return;
        }
        
        if (confirm('Are you sure you want to delete this comment?')) {
            const token = localStorage.getItem('auth_token');
            
            fetch(`{{ url('/api/comments') }}/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to delete comment');
                }
                
                // Show success message
                alert('Comment deleted successfully');
                
                // Refresh comments based on current view
                if (isAllCommentsView) {
                    fetchAllComments();
                } else {
                    loadComments(ideaId);
                }
            })
            .catch(error => {
                console.error('Error deleting comment:', error);
                alert('Failed to delete comment. Please try again.');
            });
        }
    }
    
    // Show delete confirmation modal
    function showDeleteConfirmation(ideaId) {
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        
        // Set up confirm button
        confirmDeleteBtn.onclick = () => deleteIdea(ideaId);
        
        deleteModal.show();
    }
    
    // Delete idea
    function deleteIdea(ideaId) {
        const token = localStorage.getItem('auth_token');
        
        fetch(`{{ url('/api/ideas') }}/${ideaId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                alert('Idea deleted successfully');
                window.location.href = '{{ url("/") }}';
            } else {
                throw new Error('Failed to delete idea');
            }
        })
        .catch(error => {
            console.error('Error deleting idea:', error);
            alert('Failed to delete idea. Please try again.');
        });
    }
    
    // Set up view all comments button
    function setupViewAllCommentsButton() {
        const viewAllCommentsBtn = document.getElementById('view-all-comments-btn');
        const backToIdeaBtn = document.getElementById('back-to-idea-btn');
        const refreshCommentsBtn = document.getElementById('refresh-comments-btn');
        
        if (viewAllCommentsBtn) {
            viewAllCommentsBtn.addEventListener('click', function() {
                // Hide idea container and show all comments container
                document.getElementById('idea-container').style.display = 'none';
                document.getElementById('all-comments-container').style.display = 'block';
                
                // Fetch all comments
                fetchAllComments();
            });
        }
        
        if (backToIdeaBtn) {
            backToIdeaBtn.addEventListener('click', function() {
                // Hide all comments container and show idea container
                document.getElementById('all-comments-container').style.display = 'none';
                document.getElementById('idea-container').style.display = 'block';
            });
        }
        
        if (refreshCommentsBtn) {
            refreshCommentsBtn.addEventListener('click', function() {
                // Reload comments for this idea
                loadComments(ideaId);
            });
        }
    }
    
    // Initialize everything when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        loadIdea();
        setupCommentForm();
        setupEditForm();
        setupViewAllCommentsButton();
    });
</script>
@endsection