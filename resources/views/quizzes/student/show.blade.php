@extends('layouts.master')
@section('title', $quiz->title)
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h2>{{ $quiz->title }}</h2>
                    <p class="text-muted mb-0">Created by: {{ $quiz->instructor->name }}</p>
                </div>
                <div class="card-body">
                    <h5>Description:</h5>
                    <p>{{ $quiz->description ?: 'No description provided.' }}</p>
                    
                    @if($submission)
                        <div class="alert alert-info">
                            <h5>You have already submitted your answer for this quiz</h5>
                            
                            @if($submission->status === 'pending')
                                <p>Your submission is pending review by the instructor.</p>
                            @else
                                <p>Score: <strong>{{ $submission->score }}/100</strong></p>
                                @if($submission->instructor_feedback)
                                    <h6>Instructor Feedback:</h6>
                                    <p>{{ $submission->instructor_feedback }}</p>
                                @endif
                            @endif
                            
                            <a href="{{ route('quizzes.index') }}" class="btn btn-primary">Back to Quizzes</a>
                        </div>
                    @else
                        <div class="mt-4">
                            <h5>Question:</h5>
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <p class="card-text">{{ $quiz->questions->first()->question_text ?? 'No question available.' }}</p>
                                </div>
                            </div>
                            
                            <form action="{{ route('submissions.submit', $quiz) }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="answer_text" class="form-label">Your Answer:</label>
                                    <textarea class="form-control @error('answer_text') is-invalid @enderror" id="answer_text" name="answer_text" rows="5" required>{{ old('answer_text') }}</textarea>
                                    @error('answer_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Back to Quizzes</a>
                                    <button type="submit" class="btn btn-primary">Submit Answer</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
