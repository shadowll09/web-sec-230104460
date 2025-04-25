<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeFeedbackNotifier
{
    /**
     * Handle employee access to customer feedback and cancelled orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        // Only allow employees and admins
        if (!Auth::user()->hasAnyRole(['Employee', 'Admin'])) {
            abort(403, 'Access denied. Employee privileges required.');
        }

        // Load unread feedback and cancellation notifications
        $unreadNotifications = Auth::user()->unreadNotifications()
            ->whereIn('type', [
                'App\Notifications\OrderCancelled',
                'App\Notifications\NewFeedback'
            ])
            ->get();
            
        // Make notifications available to the view
        view()->share('feedbackNotifications', $unreadNotifications);
        view()->share('unreadFeedbackCount', $unreadNotifications->count());
        
        return $next($request);
    }
}
