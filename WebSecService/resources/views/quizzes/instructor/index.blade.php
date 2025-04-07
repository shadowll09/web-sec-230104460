@extends('layouts.master')
@section('title', 'My Quizzes')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Quizzes</h1>
        <a href="{{ route('quizzes.create') }}" class="btn btn-primary">Create New Quiz</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>All Quizzes</h5>
        </div>
        <div class="card-body">
            @if($quizzes->isEmpty())
                <p class="text-center">You haven't created any quizzes yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Created</th>
                                <th>Submissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $quiz)
                                <tr>
                                    <td>{{ $quiz->title }}</td>
                                    <td>{{ Str::limit($quiz->description, 50) }}</td>
                                    <td>{{ $quiz->created_at->format('M d, Y') }}</td>
                                    <td>{{ $quiz->submissions->count() }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
