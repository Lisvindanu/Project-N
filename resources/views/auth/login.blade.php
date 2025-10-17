@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h3 class="mb-4 text-white">Login to ProjectN</h3>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-white">Email</label>
                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Password</label>
                    <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label text-white" for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-netflix w-100">Login</button>
                <p class="mt-3 text-center text-white">Don't have an account? <a href="{{ route('register') }}" class="text-danger">Register</a></p>
            </form>
        </div>
    </div>
</div>
@endsection
