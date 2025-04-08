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
                    <form action="{{ url('/+') }}" method="POST">
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
            </div>
        </div>
    </div>
</div>
@endsection