@extends('layouts.master')
@section('title', $quiz->title)
@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ $quiz->title }}</h2>
                <div>
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-warning">Edit Quiz</a>
                    <a href="{{ route('quizzes.index') }}" class="btn btn-secondary ml-2">Back to Quizzes</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h5>Description:</h5>
            <p>{{ $quiz->description ?: 'No description provided.' }}</p>
            
            <h5>Question:</h5>
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <p class="card-text">{{ $quiz->questions->first()->question_text ?? 'No question available.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Student Submissions ({{ $submissions->count() }})</h3>
        </div>
        <div class="card-body">
            @if($submissions->isEmpty())
                <p class="text-center">No submissions yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Score</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
                                    <td>{{ $submission->student->name }}</td>
                                    <td>{{ $submission->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($submission->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-success">Graded</span>
                                        @endif
                                    </td>
                                    <td>{{ $submission->status === 'graded' ? $submission->score . '/100' : 'Not graded' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#submissionModal{{ $submission->id }}">
                                            {{ $submission->status === 'pending' ? 'Grade' : 'Review' }}
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Modal for submission details and grading -->
                                <div class="modal fade" id="submissionModal{{ $submission->id }}" tabindex="-1" aria-labelledby="submissionModalLabel{{ $submission->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="submissionModalLabel{{ $submission->id }}">
                                                    Submission by {{ $submission->student->name }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Question:</h5>
                                                <p>{{ $quiz->questions->first()->question_text }}</p>
                                                
                                                <hr>
                                                
                                                <h5>Student's Answer:</h5>
                                                <div class="p-3 bg-light mb-3">
                                                    <p>{{ $submission->answer_text }}</p>
                                                </div>
                                                
                                                <form action="{{ route('submissions.grade', $submission) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="mb-3">
                                                        <label for="score{{ $submission->id }}" class="form-label">Score (out of 100)</label>
                                                        <input type="number" class="form-control" id="score{{ $submission->id }}" name="score" min="0" max="100" value="{{ $submission->score ?? '' }}" {{ $submission->status === 'graded' ? '' : 'required' }}>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="feedback{{ $submission->id }}" class="form-label">Feedback (optional)</label>
                                                        <textarea class="form-control" id="feedback{{ $submission->id }}" name="instructor_feedback" rows="3">{{ $submission->instructor_feedback }}</textarea>
                                                    </div>
                                                    
                                                    @if($submission->status === 'pending')
                                                        <button type="submit" class="btn btn-primary">Submit Grade</button>
                                                    @else
                                                        <button type="submit" class="btn btn-warning">Update Grade</button>
                                                    @endif
                                                </form>
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
