<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\User;

class FeedbackController extends Controller
{
    /**
     * List all feedback
     */
    public function index(Request $request)
    {
        // Change from role check to permission check
        if (!auth()->user()->hasPermissionTo('view_feedback')) {
            abort(403, 'You do not have permission to view feedback.');
        }

        // Get all feedback ordered by creation date
        $feedback = Feedback::orderBy('created_at', 'desc')->get();
        
        return view('feedback.index', compact('feedback'));
    }

    /**
     * Show the form for submitting feedback
     */
    public function create(Request $request)
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return redirect()->route('feedback.list.own')
            ->with('success', 'Feedback submitted successfully');
    }

    /**
     * Show a specific feedback with its responses
     */
    public function show(Request $request, Feedback $feedback)
    {
        // Check if user can view this feedback (either it's their own or they have permission)
        if (auth()->id() !== $feedback->user_id && !auth()->user()->hasPermissionTo('view_feedback')) {
            abort(403, 'You do not have permission to view this feedback');
        }
        
        return view('feedback.show', compact('feedback'));
    }

    /**
     * Store a response to a feedback
     */
    public function respond(Request $request, Feedback $feedback)
    {
        // Check if user can respond to feedback
        if (!auth()->user()->hasPermissionTo('respond_to_feedback')) {
            abort(403, 'You do not have permission to respond to feedback');
        }

        $request->validate([
            'response' => 'required|string|max:1000',
        ]);
        
        // Create response and update status
        $feedback->responses()->create([
            'user_id' => auth()->id(),
            'message' => $request->response,
        ]);
        
        $feedback->status = 'responded';
        $feedback->save();
        
        return redirect()->route('feedback.show', $feedback)
            ->with('success', 'Response added successfully');
    }
}
