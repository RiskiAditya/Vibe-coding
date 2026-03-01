<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User - Equipment Lending System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-blue-600 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">Admin Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                    <a href="{{ route('users.index') }}" class="hover:text-blue-200">Users</a>
                    <span>Welcome, {{ auth()->user()->username }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container mx-auto p-6">
            <!-- Error Messages -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold">Edit User</h2>
                <p class="text-gray-600">Update user information</p>
            </div>

            <!-- Edit User Form -->
            <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Username -->
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="username" 
                               id="username" 
                               value="{{ old('username', $user->username) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('username') border-red-500 @enderror"
                               required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">3-50 characters, letters, numbers, and underscores only</p>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password. Minimum 8 characters, must include uppercase, lowercase, and number</p>
                    </div>

                    <!-- Role -->
                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" 
                                id="role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror"
                                required>
                            <option value="">Select a role</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="borrower" {{ old('role', $user->role) === 'borrower' ? 'selected' : '' }}>Borrower</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
