@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Welcome Section with Modern Design -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-2xl shadow-2xl p-8 text-white">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold mb-3 tracking-tight">Selamat datang, {{ auth()->user()->username }}! 👋</h1>
                    <p class="text-blue-100 text-lg font-medium">Kelola sistem peminjaman alat dengan mudah dan efisien</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-user-shield text-5xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards with Modern Design -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users Card -->
        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <div class="px-3 py-1 bg-blue-50 rounded-full">
                        <span class="text-xs font-bold text-blue-600 group-hover:text-white transition-colors">AKTIF</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-gray-500 group-hover:text-blue-100 transition-colors">Total Pengguna</p>
                    <p class="text-4xl font-black text-gray-900 group-hover:text-white transition-colors">{{ $stats['total_users'] }}</p>
                    <div class="flex items-center space-x-1 text-green-600 group-hover:text-green-200 transition-colors">
                        <i class="fas fa-arrow-up text-xs"></i>
                        <span class="text-xs font-bold">Akun terdaftar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Equipment Card -->
        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-box text-white text-2xl"></i>
                    </div>
                    <div class="px-3 py-1 bg-emerald-50 rounded-full">
                        <span class="text-xs font-bold text-emerald-600 group-hover:text-white transition-colors">INVENTARIS</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-gray-500 group-hover:text-emerald-100 transition-colors">Total Alat</p>
                    <p class="text-4xl font-black text-gray-900 group-hover:text-white transition-colors">{{ $stats['total_equipment'] }}</p>
                    <div class="flex items-center space-x-1 text-emerald-600 group-hover:text-emerald-200 transition-colors">
                        <i class="fas fa-check-circle text-xs"></i>
                        <span class="text-xs font-bold">Dalam sistem</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Borrowings Card -->
        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500 to-amber-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-clipboard-list text-white text-2xl"></i>
                    </div>
                    <div class="px-3 py-1 bg-amber-50 rounded-full">
                        <span class="text-xs font-bold text-amber-600 group-hover:text-white transition-colors">AKTIF</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-gray-500 group-hover:text-amber-100 transition-colors">Peminjaman Aktif</p>
                    <p class="text-4xl font-black text-gray-900 group-hover:text-white transition-colors">{{ $stats['active_borrowings'] }}</p>
                    <div class="flex items-center space-x-1 text-amber-600 group-hover:text-amber-200 transition-colors">
                        <i class="fas fa-clock text-xs"></i>
                        <span class="text-xs font-bold">Sedang dipinjam</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-500 to-rose-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-exclamation-circle text-white text-2xl"></i>
                    </div>
                    <div class="px-3 py-1 bg-rose-50 rounded-full">
                        <span class="text-xs font-bold text-rose-600 group-hover:text-white transition-colors">PENDING</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-semibold text-gray-500 group-hover:text-rose-100 transition-colors">Permintaan Tertunda</p>
                    <p class="text-4xl font-black text-gray-900 group-hover:text-white transition-colors">{{ $stats['pending_requests'] }}</p>
                    <div class="flex items-center space-x-1 text-rose-600 group-hover:text-rose-200 transition-colors">
                        <i class="fas fa-bell text-xs"></i>
                        <span class="text-xs font-bold">Perlu perhatian</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions with Modern Design -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Aksi Cepat</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola sistem dengan mudah</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-bolt text-white text-xl"></i>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Manage Users -->
            <a href="{{ route('users.index') }}" class="group relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-blue-500">
                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors">Kelola Pengguna</h3>
                        <p class="text-sm text-gray-600">Tambah, edit, atau hapus pengguna sistem</p>
                        <div class="mt-3 flex items-center text-blue-600 font-semibold text-sm">
                            <span>Buka</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Manage Equipment -->
            <a href="{{ route('equipment.index') }}" class="group relative bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-6 hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-emerald-500">
                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-box text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors">Kelola Alat</h3>
                        <p class="text-sm text-gray-600">Tambah, edit, atau hapus alat inventaris</p>
                        <div class="mt-3 flex items-center text-emerald-600 font-semibold text-sm">
                            <span>Buka</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Manage Categories -->
            <a href="{{ route('categories.index') }}" class="group relative bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-purple-500">
                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tags text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-purple-600 transition-colors">Kelola Kategori</h3>
                        <p class="text-sm text-gray-600">Atur dan kelola kategori alat</p>
                        <div class="mt-3 flex items-center text-purple-600 font-semibold text-sm">
                            <span>Buka</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Category Statistics with Modern Design -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Statistik Kategori</h2>
                <p class="text-sm text-gray-500 mt-1">Distribusi alat per kategori</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-pie text-white text-xl"></i>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categoryStats as $index => $stat)
            @php
                $colors = [
                    ['from' => 'blue-500', 'to' => 'indigo-600', 'bg' => 'blue-50', 'text' => 'blue-600'],
                    ['from' => 'emerald-500', 'to' => 'green-600', 'bg' => 'emerald-50', 'text' => 'emerald-600'],
                    ['from' => 'amber-500', 'to' => 'orange-600', 'bg' => 'amber-50', 'text' => 'amber-600'],
                    ['from' => 'rose-500', 'to' => 'pink-600', 'bg' => 'rose-50', 'text' => 'rose-600'],
                    ['from' => 'purple-500', 'to' => 'fuchsia-600', 'bg' => 'purple-50', 'text' => 'purple-600'],
                    ['from' => 'cyan-500', 'to' => 'blue-600', 'bg' => 'cyan-50', 'text' => 'cyan-600'],
                ];
                $color = $colors[$index % count($colors)];
            @endphp
            <div class="group relative bg-gradient-to-br from-{{ $color['bg'] }} to-white rounded-xl p-5 hover:shadow-lg transition-all duration-300 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-{{ $color['from'] }} to-{{ $color['to'] }} rounded-lg flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-box text-white text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $stat['name'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Kategori alat</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="px-4 py-2 bg-white rounded-lg shadow-sm">
                            <p class="text-2xl font-black text-{{ $color['text'] }}">{{ $stat['count'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Activity with Modern Design -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-history text-white"></i>
                        </div>
                        Aktivitas Terbaru
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 ml-13">Log aktivitas sistem terkini</p>
                </div>
                <div class="px-4 py-2 bg-white rounded-lg shadow-sm">
                    <span class="text-xs font-bold text-gray-600">10 TERAKHIR</span>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <div class="space-y-6">
                @foreach($recentActivity as $index => $activity)
                <div class="group relative flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-300">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr($activity->user ? $activity->user->username : 'S', 0, 1)) }}</span>
                        </div>
                        @if($index < count($recentActivity) - 1)
                        <div class="absolute top-14 left-1/2 transform -translate-x-1/2 w-0.5 h-10 bg-gradient-to-b from-gray-300 to-transparent"></div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">
                                    <span class="text-blue-600">{{ $activity->user ? $activity->user->username : 'System' }}</span>
                                    <span class="text-gray-600 font-normal"> {{ $activity->action }}</span>
                                </p>
                                <div class="flex items-center space-x-3 mt-2">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="far fa-clock mr-1.5"></i>
                                        <span>{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="far fa-calendar mr-1.5"></i>
                                        <span>{{ $activity->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
