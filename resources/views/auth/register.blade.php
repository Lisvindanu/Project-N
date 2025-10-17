@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="mb-4 text-white">Register to ProjectN</h3>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-white">Name</label>
                    <input type="text" name="name" class="form-control bg-dark text-white border-secondary" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Password</label>
                    <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-secondary" required>
                </div>
                <button type="submit" class="btn btn-netflix w-100">Register</button>
                <p class="mt-3 text-center text-white">Already have an account? <a href="{{ route('login') }}" class="text-danger">Login</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
