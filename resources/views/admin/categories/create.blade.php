@extends('layouts.app')
@section('content')
<h2>Add Category</h2>
<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-netflix">Create</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light">Cancel</a>
</form>
@endsection
