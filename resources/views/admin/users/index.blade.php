@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h2>
        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
            <i class="fas fa-plus mr-2"></i>Tambah Pengguna Baru
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-users mr-2"></i>
                Daftar Pengguna
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Username
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Peran
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Dibuat Pada
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $user->username }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->role === 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role === 'staff') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="far fa-calendar mr-1"></i>
                                {{ $user->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors mr-2">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                @if($user->id !== auth()->id() && $user->role !== 'admin')
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500 font-medium">Tidak ada pengguna ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
