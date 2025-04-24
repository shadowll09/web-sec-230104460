<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of feedback.
     */
    public function index()
    {
        // Check permissions - only admin and employees can view all feedback
        if (!Auth::user()->hasAnyRole(['Admin', 'Employee'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $feedbacks = Feedback::with(['user', 'order'])
            ->latest()
            ->paginate(10);
            
        return view('feedback.index', compact('feedbacks'));
    }
    
    /**
     * Show specific feedback details.
     */
    public function show(Feedback $feedback)
    {
        // Check permissions
        if (Auth::id() != $feedback->user_id && !Auth::user()->hasAnyRole(['Admin', 'Employee'])) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('feedback.show', compact('feedback'));
    }
    
    /**
     * Update feedback with admin response.
     */
    public function respond(Request $request, Feedback $feedback)
    {
        // Check permissions
        if (!Auth::user()->hasAnyRole(['Admin', 'Employee'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);
        
        $feedback->admin_response = $request->admin_response;
        $feedback->resolved = true;
        $feedback->resolved_by = Auth::id();
        $feedback->resolved_at = now();
        $feedback->save();
        
        return redirect()->route('feedback.show', $feedback->id)
            ->with('success', 'Response submitted successfully.');
    }
}
