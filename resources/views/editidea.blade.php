@extends('layouts.app') 

@section('title', 'Edit Idea') 

@section('content') 
<div class="container my-4">
    <!-- Edit Idea Form -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto"> 
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h5>Edit Idea</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/update-idea/' . $idea->id) }}" method="POST">
                        @csrf <!-- Laravel CSRF token for security -->
                        @method('PUT') <!-- Use PUT method for updating -->
                        <div class="mb-3">
                            <label for="ideaTitle" class="form-label">Idea Title</label>
                            <input type="text" class="form-control" id="ideaTitle" name="title" value="{{ $idea->title }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="ideaDescription" class="form-label">Idea Description</label>
                            <textarea class="form-control" id="ideaDescription" name="description" rows="3" required>{{ $idea->description }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Idea</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection