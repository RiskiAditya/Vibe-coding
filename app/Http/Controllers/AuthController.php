<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show the login form
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = $this->authService->authenticate(
            $request->input('username'),
            $request->input('password')
        );

        if (!$user) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Invalid credentials. Please try again.']);
        }

        // Create session
        $this->authService->createSession($user);

        // Redirect to role-appropriate dashboard
        return $this->redirectToDashboard($user->role);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->destroySession();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect to role-appropriate dashboard
     */
    protected function redirectToDashboard(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            'borrower' => redirect()->route('borrower.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
