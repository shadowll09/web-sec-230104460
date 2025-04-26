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
        // Check permissions - user needs view_customer_feedback permission
        if (!Auth::user()->hasPermissionTo('view_customer_feedback')) {
            abort(403, 'Unauthorized action. You need view_customer_feedback permission.');
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
        // Check permissions - user needs to be the feedback owner or have view_customer_feedback permission
        if (Auth::id() != $feedback->user_id && !Auth::user()->hasPermissionTo('view_customer_feedback')) {
            abort(403, 'Unauthorized action. You need view_customer_feedback permission.');
        }
        
        return view('feedback.show', compact('feedback'));
    }
    
    /**
     * Update feedback with admin response.
     */
    public function respond(Request $request, Feedback $feedback)
    {
        // Check permissions - user needs respond_to_feedback permission
        if (!Auth::user()->hasPermissionTo('respond_to_feedback')) {
            abort(403, 'Unauthorized action. You need respond_to_feedback permission.');
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
