<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Authenticate user with username and password
     */
    public function authenticate(string $username, string $password): ?User
    {
        $user = User::where('username', $username)->first();

        if (!$user || !$this->verifyPassword($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Hash password using bcrypt
     */
    public function hashPassword(string $password): string
    {
        return Hash::make($password);
    }

    /**
     * Verify password against hash
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }

    /**
     * Create session for user
     */
    public function createSession(User $user): void
    {
        // Log the user in
        Auth::login($user);

        // Regenerate session to prevent session fixation
        request()->session()->regenerate();

        // Log the login activity
        $this->logActivity($user, 'login', 'User logged in');
    }

    /**
     * Destroy user session
     */
    public function destroySession(): void
    {
        $user = Auth::user();

        if ($user) {
            // Log the logout activity before destroying session
            $this->logActivity($user, 'logout', 'User logged out');
        }

        // Logout the user
        Auth::logout();

        // Invalidate the session
        request()->session()->invalidate();

        // Regenerate CSRF token
        request()->session()->regenerateToken();
    }

    /**
     * Log activity to activity_logs table
     */
    protected function logActivity(User $user, string $action, string $details): void
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
