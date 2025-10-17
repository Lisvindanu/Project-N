@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-netflix">Add Category</a>
</div>

<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Videos Count</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->videos_count }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-light">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
