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
           'meta_title'=> 'Dashboard | LMS Dashboard',
           'meta_desc'=> 'Learning management system',
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

            return redirect()->intended(route('dashboard', absolute: false));
            
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}