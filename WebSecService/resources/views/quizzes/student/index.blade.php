@extends('layouts.master')
@section('title', 'Available Quizzes')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Available Quizzes</h1>
        <a href="{{ route('student.results') }}" class="btn btn-primary">View My Results</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Quizzes</h5>
        </div>
        <div class="card-body">
            @if($quizzes->isEmpty())
                <p class="text-center">No quizzes available at the moment.</p>
            @else
                <div class="row">
                    @foreach($quizzes as $quiz)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title">{{ $quiz->title }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Created by: {{ $quiz->instructor->name }}</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ Str::limit($quiz->description, 100) }}</p>
                                </div>
                                <div class="card-footer">
                                    @if(isset($submissions[$quiz->id]))
                                        @if($submissions[$quiz->id]->status === 'pending')
                                            <span class="badge bg-warning">Submitted - Pending Grade</span>
                                        @else
                                            <span class="badge bg-success">Graded - Score: {{ $submissions[$quiz->id]->score }}/100</span>
                                        @endif
                                    @else
                                        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-primary">Take Quiz</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
