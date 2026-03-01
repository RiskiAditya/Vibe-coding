<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Equipment Lending System')</title>
    
    <!-- Tailwind CSS CDN for immediate fix -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        }
        
        .gradient-accent {
            background: linear-gradient(135deg, #F97316 0%, #EA580C 100%);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease-in-out;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 40px -5px rgba(0, 0, 0, 0.2), 0 15px 30px -10px rgba(0, 0, 0, 0.15);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    @auth
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0" 
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 gradient-primary">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-box text-white text-2xl"></i>
                    <span class="text-white font-bold text-lg">ATMIN</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs(auth()->user()->role . '.dashboard') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-home w-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('equipment.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('equipment.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-box w-5"></i>
                    <span class="font-medium">Alat</span>
                </a>
                
                <a href="{{ route('borrowings.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('borrowings.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span class="font-medium">Peminjaman</span>
                </a>
                
                @if(auth()->user()->role === 'staff')
                <a href="{{ route('returns.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('returns.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-undo w-5"></i>
                    <span class="font-medium">Pengembalian</span>
                </a>
                
                <a href="{{ route('penalties.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('penalties.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-money-bill-wave w-5"></i>
                    <span class="font-medium">Manajemen Denda</span>
                </a>
                
                <a href="{{ route('damaged-equipment.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('damaged-equipment.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-tools w-5"></i>
                    <span class="font-medium">List Barang Rusak</span>
                </a>
                
                <a href="{{ route('lost-equipment.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('lost-equipment.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-question-circle w-5"></i>
                    <span class="font-medium">List Barang Hilang</span>
                </a>
                
                <a href="{{ route('borrower-history.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('borrower-history.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-user-clock w-5"></i>
                    <span class="font-medium">Riwayat Peminjam</span>
                </a>
                
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="font-medium">Laporan</span>
                </a>
                @endif
                
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('returns.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('returns.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                    <i class="fas fa-undo w-5"></i>
                    <span class="font-medium">Pengembalian</span>
                </a>
                @endif
                
                @if(auth()->user()->role === 'admin')
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin</p>
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('users.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span class="font-medium">Pengguna</span>
                    </a>
                    
                    <a href="{{ route('categories.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('categories.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <i class="fas fa-tags w-5"></i>
                        <span class="font-medium">Kategori</span>
                    </a>
                </div>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->username }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="font-medium">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Main Content Area -->
    <div class="lg:pl-64">
        <!-- Top Header -->
        <header class="bg-white shadow-sm sticky top-0 z-30">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div class="flex-1 flex items-center justify-between">
                    <h1 class="text-xl font-bold text-gray-900 hidden lg:block">@yield('page-title', 'Dashboard')</h1>
                    
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-500">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ now()->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-full"
             class="fixed top-20 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">Berhasil</p>
                    <p class="mt-1 text-sm text-gray-500">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform translate-x-full"
             class="fixed top-20 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">Kesalahan</p>
                    <p class="mt-1 text-sm text-gray-500">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <main class="p-4 sm:p-6 lg:p-8 animate-fade-in">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-8">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <p class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Sistem Peminjaman Alat. Hak cipta dilindungi.
                </p>
            </div>
        </footer>
    </div>
    @else
    <!-- Guest Layout (Login Page) -->
    <main>
        @yield('content')
    </main>
    @endauth
</body>
</html>
