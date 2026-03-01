@extends('layouts.app')

@section('title', 'Dashboard Peminjam')
@section('page-title', 'Dashboard Peminjam')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat datang, {{ auth()->user()->username }}! 🎉</h2>
        <p class="text-gray-600">Jelajahi alat yang tersedia dan kelola peminjaman Anda.</p>
    </div>
    
    <!-- My Borrowings Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-primary-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Permintaan Tertunda</p>
                    <p class="text-3xl font-bold text-gray-900">{{ collect($myBorrowings->items())->where('status', 'pending')->count() }}</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-clock mr-1"></i>Menunggu persetujuan
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full gradient-primary flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Peminjaman Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ collect($myBorrowings->items())->where('status', 'approved')->count() }}</p>
                    <p class="text-xs text-yellow-600 mt-2">
                        <i class="fas fa-box mr-1"></i>Sedang dipinjam
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Dipinjam</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $myBorrowings->total() }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-history mr-1"></i>Sepanjang waktu
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full gradient-success flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Equipment -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-box text-primary-500 mr-2"></i>
                Alat Tersedia
            </h3>
            <a href="{{ route('equipment.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                Lihat Semua
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($availableEquipment->take(6) as $item)
            <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover group">
                <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden">
                    @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                    <i class="fas fa-box text-gray-300 text-6xl group-hover:scale-110 transition-transform duration-300"></i>
                    @endif
                </div>
                <div class="p-5">
                    <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">{{ $item->name }}</h4>
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-xs font-semibold">
                            <i class="fas fa-tag mr-1"></i>{{ $item->category->name }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $item->description }}</p>
                    <a href="{{ route('borrowings.create', ['equipment_id' => $item->id]) }}" 
                       class="block w-full text-center gradient-accent text-white px-4 py-2.5 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-hand-holding mr-2"></i>Pinjam Sekarang
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-xl shadow-md">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak ada alat tersedia</h3>
                <p class="text-gray-500">Periksa kembali nanti untuk alat yang tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- My Recent Borrowings -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
        <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-history mr-2"></i>
                Peminjaman Terbaru Saya
            </h3>
            <a href="{{ route('borrowings.index') }}" class="text-white hover:text-gray-200 text-sm font-medium flex items-center">
                Lihat Semua
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Alat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($myBorrowings as $borrowing)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg gradient-primary flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $borrowing->equipment->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $borrowing->borrow_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="far fa-calendar-check mr-1"></i>
                            @if($borrowing->actual_return_date)
                                {{ $borrowing->actual_return_date->format('M d, Y') }}
                            @else
                                {{ $borrowing->requested_return_date->format('M d, Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full
                                @if($borrowing->status === 'pending') bg-blue-100 text-blue-800
                                @elseif($borrowing->status === 'approved') bg-yellow-100 text-yellow-800
                                @elseif($borrowing->status === 'rejected') bg-red-100 text-red-800
                                @elseif($borrowing->status === 'pending_return') bg-purple-100 text-purple-800
                                @else bg-green-100 text-green-800
                                @endif">
                                @if($borrowing->status === 'pending')
                                    <i class="fas fa-clock mr-1"></i>
                                @elseif($borrowing->status === 'approved')
                                    <i class="fas fa-check mr-1"></i>
                                @elseif($borrowing->status === 'rejected')
                                    <i class="fas fa-times mr-1"></i>
                                @elseif($borrowing->status === 'pending_return')
                                    <i class="fas fa-undo mr-1"></i>
                                @else
                                    <i class="fas fa-check-circle mr-1"></i>
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($borrowing->status === 'approved')
                                <a href="{{ route('returns.create', $borrowing) }}" class="inline-flex items-center px-3 py-1.5 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors">
                                    <i class="fas fa-undo mr-1"></i>
                                    Kembalikan
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                            <p class="text-gray-500 font-medium">Belum ada riwayat peminjaman.</p>
                            <p class="text-gray-400 text-sm mt-1">Mulai dengan meminjam alat!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($myBorrowings->hasPages())
        <div class="px-6 py-4 bg-gray-50">
            {{ $myBorrowings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
