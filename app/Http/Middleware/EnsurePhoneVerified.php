<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsurePhoneVerified
{
    /**
     * Handle an incoming request.
     *
     * This store verifies customers through phone OTP verification.
     * The phone OTP flow stores the verification flag in email_verified_at.
     * Users who are not verified yet are sent to the phone OTP verification page
     * instead of Laravel's default email verification page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() &&
            Auth::user()->user_type == 'customer' &&
            Auth::user() instanceof MustVerifyEmail &&
            ! Auth::user()->hasVerifiedEmail()) {
            session(['link' => url()->current()]);
            return redirect()->route('verification');
        }

        return $next($request);
    }
}
