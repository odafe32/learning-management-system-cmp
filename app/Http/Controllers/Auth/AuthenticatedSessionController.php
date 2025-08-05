<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $viewData = [
           'meta_title'=> 'Login | LMS Dashboard',
           'meta_desc'=> 'Sign in to your Learning Management System account',
           'meta_image'=> url('pwa_assets/android-chrome-256x256.png'),
        ];

        return view('auth.login', $viewData);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Debug logging
            Log::info('Login attempt', [
                'email' => $request->email,
                'has_password' => !empty($request->password),
                'csrf_token' => $request->header('X-CSRF-TOKEN') ?? 'missing',
                'session_token' => session()->token(),
            ]);

            $request->authenticate();

            $request->session()->regenerate();

            Log::info('Login successful for: ' . $request->email);

            // Get user for personalized message
            $user = Auth::user();
            $userName = $user->name ?? 'User';
            
            // Flash success message with user's name
            $request->session()->flash('success', "Welcome back, {$userName}! You have been successfully logged in.");

            return redirect()->intended(route('dashboard', absolute: false));
            
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Flash error message for login failure
            $request->session()->flash('error', 'Login failed. Please check your credentials and try again.');
            
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Get user name before logging out
        $user = Auth::user();
        $userName = $user->name ?? 'User';
        
        Log::info('User logging out: ' . ($user->email ?? 'Unknown'));

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Flash logout success message
        $request->session()->flash('success', "Goodbye, {$userName}! You have been successfully logged out. See you next time!");

        // Redirect to login page with success message
        return redirect()->route('login');
    }
    
    /**
     * Handle logout from dashboard with AJAX
     */
    public function logoutAjax(Request $request)
    {
        try {
            // Get user name before logging out
            $user = Auth::user();
            $userName = $user->name ?? 'User';
            $userEmail = $user->email ?? 'Unknown';
            
            Log::info('User logging out via AJAX: ' . $userEmail);

            // Create the success message
            $logoutMessage = "Goodbye, {$userName}! You have been successfully logged out. See you next time!";

            // Logout the user
            Auth::guard('web')->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the token
            $request->session()->regenerateToken();

            // Flash the success message to the NEW session
            // We need to start a new session and flash the message
            $request->session()->start();
            $request->session()->flash('success', $logoutMessage);

            Log::info('AJAX logout successful for: ' . $userEmail);

            return response()->json([
                'success' => true,
                'message' => $logoutMessage,
                'redirect' => route('login')
            ]);

        } catch (\Exception $e) {
            Log::error('AJAX logout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Logout failed. Please try again.',
                'redirect' => route('login')
            ], 500);
        }
    }
}