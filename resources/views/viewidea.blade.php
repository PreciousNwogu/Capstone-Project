@extends('layouts.app')

@section('title', $idea['title'])

@section('content')
<div class="container my-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Idea Details -->
            <div class="card bg-dark text-light">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ $idea['title'] }}</h3>
                    <div class="d-flex gap-2">
                        <!-- Edit Button -->
                        <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm" onclick="handleEdit({{ $idea['id'] }})">Edit</a>

                        <!-- Delete Button -->
                        <form action="{{ url('/delete-idea/' . $idea['id']) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this idea?')">Delete</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <p>{{ $idea['description'] }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-primary">Likes: {{ $idea['likes'] }}</span>
                        <button class="btn btn-outline-primary">Like</button>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card bg-dark text-light mt-4">
                <div class="card-header">
                    <h5>Comments</h5>
                </div>
                <div class="card-body">
                    @if(count($idea['comments']) > 0)
                        <ul class="list-group">
                            @foreach($idea['comments'] as $comment)
                                <li class="list-group-item bg-dark text-light border-secondary">
                                    <strong>{{ $comment['author'] }}:</strong>
                                    <p>{{ $comment['content'] }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center">No comments yet.</p>
                    @endif
                </div>
            </div>

            <!-- Add Comment Section -->
            <div class="card bg-dark text-light mt-4">
                <div class="card-header">
                    <h5>Add a Comment</h5>
                </div>
                <div class="card-body">
                    @if(auth()->check())
                        <form action="#" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="comment" class="form-label">Your Comment</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Comment</button>
                        </form>
                    @else
                        <p class="text-center">You need to be logged in to submit a comment.</p>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Login or Sign Up</button>
                    @endif
                </div>
            </div>

            <!-- Back to Home Button -->
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="btn btn-outline-light">Back to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection

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
</script>