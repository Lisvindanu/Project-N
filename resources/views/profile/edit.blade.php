@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4 text-white">Edit Profile</h2>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center mb-4">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="rounded-circle" width="150" height="150" style="object-fit: cover; border: 3px solid #e50914;">
                        @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px; border: 3px solid #e50914;">
                                <span class="text-white display-4">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control bg-dark text-white border-secondary" accept="image/*">
                        <small class="text-muted">Max 2MB (JPG, JPEG, PNG)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Name</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary" value="{{ auth()->user()->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white">Email</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ auth()->user()->email }}" required>
                    </div>

                    <button type="submit" class="btn btn-netflix">Update Profile</button>
                    <a href="{{ route('home') }}" class="btn btn-outline-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
