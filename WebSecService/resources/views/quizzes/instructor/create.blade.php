@extends('layouts.master')
@section('title', 'Create Quiz')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Create New Quiz</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('quizzes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Quiz Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question</label>
                            <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text" name="question_text" rows="5" required>{{ old('question_text') }}</textarea>
                            <small class="form-text text-muted">Enter the question that students will answer.</small>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
