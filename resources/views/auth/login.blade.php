<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Peminjaman Alat</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        h1, h2, h3 {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        /* Hide browser's default password reveal button */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear,
        input[type="password"]::-webkit-credentials-auto-fill-button,
        input[type="password"]::-webkit-contacts-auto-fill-button {
            display: none !important;
            visibility: hidden !important;
            pointer-events: none !important;
            position: absolute !important;
            right: 0 !important;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .demo-card {
            transition: all 0.3s ease;
        }
        
        .demo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Branding -->
            <div class="text-white space-y-6 hidden md:block">
                <div class="floating">
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 border border-white border-opacity-20">
                        <i class="fas fa-box-open text-6xl mb-4"></i>
                        <h1 class="text-4xl font-bold mb-3">Sistem Peminjaman Alat</h1>
                        <p class="text-lg text-white text-opacity-90">Kelola peminjaman alat dengan mudah dan efisien</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4 mt-8">
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-4 text-center border border-white border-opacity-20">
                        <i class="fas fa-users text-3xl mb-2"></i>
                        <p class="text-sm font-medium">Multi User</p>
                    </div>
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-4 text-center border border-white border-opacity-20">
                        <i class="fas fa-shield-alt text-3xl mb-2"></i>
                        <p class="text-sm font-medium">Aman</p>
                    </div>
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-4 text-center border border-white border-opacity-20">
                        <i class="fas fa-chart-line text-3xl mb-2"></i>
                        <p class="text-sm font-medium">Laporan</p>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="glass-effect rounded-2xl shadow-2xl p-8 md:p-10">
                <!-- Logo Mobile -->
                <div class="md:hidden text-center mb-6">
                    <i class="fas fa-box-open text-5xl text-purple-600 mb-3"></i>
                    <h2 class="text-2xl font-bold text-gray-800">Sistem Peminjaman Alat</h2>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang!</h2>
                    <p class="text-gray-600">Silakan login untuk melanjutkan</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- Error Message -->
                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                        <div class="flex-1">
                            <p class="text-red-700 font-medium">Terjadi Kesalahan:</p>
                            <ul class="mt-1 text-sm text-red-600">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-purple-600"></i>Username
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            required 
                            autofocus
                            maxlength="25"
                            class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none text-gray-800 placeholder-gray-400"
                            placeholder="Masukkan username Anda">
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-600"></i>Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                maxlength="20"
                                autocomplete="current-password"
                                class="input-field w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none text-gray-800 placeholder-gray-400"
                                placeholder="Masukkan password Anda"
                                style="padding-right: 3rem;">
                            <button 
                                type="button" 
                                onclick="togglePassword(); return false;"
                                tabindex="-1"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-purple-600 focus:outline-none transition-colors"
                                style="pointer-events: auto; z-index: 10;">
                                <i id="toggleIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="btn-login w-full py-3.5 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </form>

                <!-- Demo Accounts -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-center text-sm font-semibold text-gray-700 mb-4">
                        <i class="fas fa-info-circle mr-1 text-purple-600"></i>Akun Demo
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Admin -->
                        <div class="demo-card bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center justify-center mb-2">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-shield text-white"></i>
                                </div>
                            </div>
                            <p class="text-center font-bold text-blue-900 text-sm mb-1">Admin</p>
                            <p class="text-center text-xs text-blue-700 font-mono">admin</p>
                            <p class="text-center text-xs text-blue-700 font-mono">Admin123</p>
                        </div>

                        <!-- Staff -->
                        <div class="demo-card bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center justify-center mb-2">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-white"></i>
                                </div>
                            </div>
                            <p class="text-center font-bold text-green-900 text-sm mb-1">Staff</p>
                            <p class="text-center text-xs text-green-700 font-mono">staff</p>
                            <p class="text-center text-xs text-green-700 font-mono">Staff123</p>
                        </div>

                        <!-- Borrower -->
                        <div class="demo-card bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                            <div class="flex items-center justify-center mb-2">
                                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </div>
                            <p class="text-center font-bold text-orange-900 text-sm mb-1">Borrower</p>
                            <p class="text-center text-xs text-orange-700 font-mono">borrower</p>
                            <p class="text-center text-xs text-orange-700 font-mono">Borrower123</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        © {{ date('Y') }} Sistem Peminjaman Alat. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
