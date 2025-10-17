@extends('layouts.app')
@section('content')
<h2>Edit Category</h2>
<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Category Name</label>
        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
    </div>
    <button type="submit" class="btn btn-netflix">Update</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light">Cancel</a>
</form>
@endsection
