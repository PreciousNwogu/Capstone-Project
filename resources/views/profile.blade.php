@extends('layouts.app') 

@section('title', 'Profile') 

@section('content') 
<div class="container my-4">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card bg-dark text-light">
                <div class="card-header text-center">
                    <h3>Profile</h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ asset('storage/profile.webp') }}" alt="Profile Picture" class="rounded-circle" width="100">
                    </div>
                    <h4>{{ $user->full_name }}</h4>
                    <p>{{ $user->username }}</p>
                    <p>{{ $user->email }}</p>

                    <!-- Edit Profile Button -->
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>

                    <!-- Cover Photo Upload Form -->
                    <form action="{{ url('/profile/upload-cover') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="cover_photo" id="coverPhoto" accept=".pdf, .svg, .jpg" class="form-control @error('cover_photo') is-invalid @enderror">
                            @error('cover_photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Upload Cover Photo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light"> 
            <div class="modal-header border-secondary"> 
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button> 
            <div class="modal-body">
                <form action="{{ url('/profile/update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name:</label>
                        <input type="text" class="form-control bg-dark text-light" name="full_name" value="{{ $user->full_name }}"> 
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username:</label>
                        <input type="text" class="form-control bg-dark text-light" name="username" value="{{ $user->username }}"> 
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" class="form-control bg-dark text-light" name="email" value="{{ $user->email }}"> 
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password:</label>
                        <input type="password" class="form-control bg-dark text-light" name="password"> 
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection