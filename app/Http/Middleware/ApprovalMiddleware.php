<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApprovalMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated as 'web' or 'customer'
        $user = Auth::guard('web')->user();

        if ($user) {
            // If the user is not approved
            if (!$user->approved) {
                // Logout the user
                Auth::guard('web')->logout();
                Auth::guard('customer')->logout();

                // Redirect to login with a message
                return redirect()->route('login')->with('message', trans('global.yourAccountNeedsAdminApproval'));
            }
        }

        // Continue with the request if the user is approved
        return $next($request);
    }
}

