@extends('layouts.master')
@section('title', 'My Quiz Results')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Quiz Results</h1>
        <a href="{{ route('quizzes.index') }}" class="btn btn-primary">Back to Quizzes</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Results</h5>
        </div>
        <div class="card-body">
            @if($submissions->isEmpty())
                <p class="text-center">You haven't taken any quizzes yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Quiz</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
                                    <td>{{ $submission->quiz->title }}</td>
                                    <td>{{ $submission->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($submission->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-success">Graded</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->status === 'graded')
                                            {{ $submission->score }}/100
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#resultModal{{ $submission->id }}">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Modal for result details -->
                                <div class="modal fade" id="resultModal{{ $submission->id }}" tabindex="-1" aria-labelledby="resultModalLabel{{ $submission->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="resultModalLabel{{ $submission->id }}">
                                                    {{ $submission->quiz->title }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Question:</h5>
                                                <p>{{ $submission->quiz->questions->first()->question_text }}</p>
                                                
                                                <hr>
                                                
                                                <h5>Your Answer:</h5>
                                                <div class="p-3 bg-light mb-3">
                                                    <p>{{ $submission->answer_text }}</p>
                                                </div>
                                                
                                                <hr>
                                                
                                                <h5>Status: 
                                                    @if($submission->status === 'pending')
                                                        <span class="badge bg-warning">Pending Review</span>
                                                    @else
                                                        <span class="badge bg-success">Graded</span>
                                                    @endif
                                                </h5>
                                                
                                                @if($submission->status === 'graded')
                                                    <h5>Score: {{ $submission->score }}/100</h5>
                                                    
                                                    @if($submission->instructor_feedback)
                                                        <h5>Instructor Feedback:</h5>
                                                        <div class="p-3 bg-light">
                                                            <p>{{ $submission->instructor_feedback }}</p>
                                                        </div>
                                                    @else
                                                        <p>No feedback provided.</p>
                                                    @endif
                                                @else
                                                    <p>Your submission is still pending review by the instructor.</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
