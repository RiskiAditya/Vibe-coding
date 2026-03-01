<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        try {
            $this->userService->createUser($request->validated());
            
            return redirect()
                ->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->userService->updateUser($user, $request->validated());
            
            return redirect()
                ->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $currentUser = Auth::user();
            
            if (!$this->userService->canDeleteUser($user, $currentUser)) {
                return back()->with('error', 'You cannot delete your own account.');
            }
            
            $this->userService->deleteUser($user);
            
            return redirect()
                ->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
