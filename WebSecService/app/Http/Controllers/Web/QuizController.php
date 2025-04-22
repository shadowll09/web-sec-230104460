<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizSubmission;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        // Check user role to determine what quizzes to show
        if (auth()->user()->hasRole('Instructor')) {
            // Instructors see their own quizzes
            $quizzes = Quiz::where('instructor_id', auth()->id())->get();
            return view('quizzes.instructor.index', compact('quizzes'));
        } else {
            // Students see available quizzes
            $quizzes = Quiz::all();
            $submissions = QuizSubmission::where('student_id', auth()->id())->get()
                ->keyBy('quiz_id');
            
            return view('quizzes.student.index', compact('quizzes', 'submissions'));
        }
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create_quiz')) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('quizzes.instructor.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create_quiz')) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'question_text' => 'required|string',
        ]);
        
        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructor_id' => auth()->id(),
        ]);
        
        // Create the single question for this quiz
        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
        ]);
        
        return redirect()->route('quizzes.index')->with('success', 'Quiz created successfully');
    }

    public function show(Quiz $quiz)
    {
        // For instructors: show quiz details and submissions
        if (auth()->user()->hasRole('Instructor')) {
            if (auth()->id() !== $quiz->instructor_id) {
                abort(403, 'You do not have permission to view this quiz.');
            }
            
            $submissions = $quiz->submissions()->with('student')->get();
            return view('quizzes.instructor.show', compact('quiz', 'submissions'));
        }
        
        // For students: show quiz details and submission form
        if (!auth()->user()->hasPermissionTo('take_quiz')) {
            abort(403, 'Unauthorized action.');
        }
        
        $submission = QuizSubmission::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->first();
            
        return view('quizzes.student.show', compact('quiz', 'submission'));
    }

    public function edit(Quiz $quiz)
    {
        if (!auth()->user()->hasPermissionTo('edit_quiz')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (auth()->id() !== $quiz->instructor_id) {
            abort(403, 'You do not have permission to edit this quiz.');
        }
        
        return view('quizzes.instructor.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        if (!auth()->user()->hasPermissionTo('edit_quiz')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (auth()->id() !== $quiz->instructor_id) {
            abort(403, 'You do not have permission to edit this quiz.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'question_text' => 'required|string',
        ]);
        
        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);
        
        // Update the question
        $question = $quiz->questions->first();
        if ($question) {
            $question->update([
                'question_text' => $validated['question_text'],
            ]);
        }
        
        return redirect()->route('quizzes.show', $quiz)->with('success', 'Quiz updated successfully');
    }

    public function destroy(Quiz $quiz)
    {
        if (!auth()->user()->hasPermissionTo('delete_quiz')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (auth()->id() !== $quiz->instructor_id) {
            abort(403, 'You do not have permission to delete this quiz.');
        }
        
        $quiz->delete();
        
        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully');
    }
}
