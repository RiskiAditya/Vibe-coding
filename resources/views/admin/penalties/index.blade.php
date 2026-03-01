@extends('layouts.app')

@section('title', 'Manajemen Denda')
@section('page-title', 'Manajemen Denda')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-red-500 transform transition-all hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Denda</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_penalties'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-600 mt-2">
                        <i class="fas fa-receipt mr-1"></i>{{ $stats['total_count'] }} transaksi
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-yellow-500 transform transition-all hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Belum Dibayar</p>
                    <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($stats['unpaid_penalties'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-600 mt-2">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $stats['unpaid_count'] }} transaksi
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-green-500 transform transition-all hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Sudah Dibayar</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['paid_penalties'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-600 mt-2">
                        <i class="fas fa-check-circle mr-1"></i>{{ $stats['paid_count'] }} transaksi
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full gradient-success flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-double text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-blue-500 transform transition-all hover:scale-105">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 mb-1">Tingkat Pembayaran</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $stats['total_count'] > 0 ? number_format(($stats['paid_count'] / $stats['total_count']) * 100, 1) : 0 }}%
                    </p>
                    <p class="text-xs text-gray-600 mt-2">
                        <i class="fas fa-chart-line mr-1"></i>Dari total denda
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full gradient-primary flex items-center justify-center shadow-lg">
                    <i class="fas fa-percentage text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-filter mr-2 text-primary-600"></i>Filter & Pencarian
            </h3>
        </div>
        <form method="GET" action="{{ route('penalties.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>Cari Peminjam / Alat
                </label>
                <input type="text" name="search" id="search" value="{{ $search }}" 
                       placeholder="Ketik nama peminjam atau nama alat..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
            </div>
            <div class="w-full md:w-56">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1"></i>Status Pembayaran
                </label>
                <select name="status" id="status" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>
                        <i class="fas fa-list"></i> Semua Status
                    </option>
                    <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>
                        <i class="fas fa-clock"></i> Belum Dibayar
                    </option>
                    <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>
                        <i class="fas fa-check"></i> Sudah Dibayar
                    </option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" style="display: inline-block; padding: 0.625rem 1.5rem; background-color: #3b82f6; color: white; border-radius: 0.5rem; font-weight: 500;">
                    <i class="fas fa-search" style="margin-right: 0.5rem;"></i>Cari
                </button>
                <a href="{{ route('penalties.index') }}" style="display: inline-block; padding: 0.625rem 1.5rem; background-color: #d1d5db; color: #374151; border-radius: 0.5rem; font-weight: 500; text-decoration: none;">
                    <i class="fas fa-redo" style="margin-right: 0.5rem;"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Penalties Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div style="padding: 1rem 1.5rem; background: linear-gradient(to right, #3b82f6, #2563eb); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #ffffff; margin: 0; display: inline-block;">
                <i class="fas fa-list" style="margin-right: 0.5rem;"></i><span style="color: #ffffff;">Daftar Denda</span>
            </h3>
            <div style="color: white; font-size: 0.875rem;">
                @if($status === 'unpaid')
                    <span style="background-color: rgba(255, 255, 255, 0.2); padding: 0.25rem 0.75rem; border-radius: 9999px; display: inline-block;">
                        <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>Belum Dibayar
                    </span>
                @elseif($status === 'paid')
                    <span style="background-color: rgba(255, 255, 255, 0.2); padding: 0.25rem 0.75rem; border-radius: 9999px; display: inline-block;">
                        <i class="fas fa-check-circle" style="margin-right: 0.25rem;"></i>Sudah Dibayar
                    </span>
                @else
                    <span style="background-color: rgba(255, 255, 255, 0.2); padding: 0.25rem 0.75rem; border-radius: 9999px; display: inline-block;">
                        <i class="fas fa-list-ul" style="margin-right: 0.25rem;"></i>Semua Status
                    </span>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i>Peminjam
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-box mr-1"></i>Alat
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-1"></i>Tgl Kembali
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Keterlambatan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-clock mr-1"></i>Denda
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-wrench mr-1"></i>Biaya Perbaikan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-money-bill-wave mr-1"></i>Total
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-1"></i>Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <i class="fas fa-cog mr-1"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penalties as $penalty)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $penalty->user->username }}</div>
                                    <div class="text-xs text-gray-500">{{ $penalty->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $penalty->equipment->name }}</div>
                            <div class="text-xs text-gray-500">{{ $penalty->equipment->category->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $penalty->actual_return_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($penalty->late_days > 0)
                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $penalty->late_days }} hari
                                </span>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                            Rp {{ number_format($penalty->late_fee, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-orange-600">
                            Rp {{ number_format($penalty->repair_cost, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-base font-bold text-red-700">
                            Rp {{ number_format($penalty->total_penalty, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($penalty->penalty_paid)
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Lunas
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $penalty->penalty_paid_at->format('d M Y') }}
                                    </div>
                                </div>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($penalty->penalty_paid)
                                <form action="{{ route('penalties.mark-unpaid', $penalty) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Yakin ingin mengubah status menjadi belum dibayar?')"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                                        <i class="fas fa-undo mr-1"></i>
                                        Batalkan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('penalties.mark-paid', $penalty) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Konfirmasi bahwa denda sudah dibayar?')"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                        <i class="fas fa-check mr-1"></i>
                                        Tandai Lunas
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                            @if($status === 'unpaid')
                                <p class="text-gray-500 font-medium">Tidak ada denda yang belum dibayar.</p>
                                <p class="text-gray-400 text-sm mt-1">Semua denda sudah lunas! 🎉</p>
                            @elseif($status === 'paid')
                                <p class="text-gray-500 font-medium">Tidak ada denda yang sudah dibayar.</p>
                                <p class="text-gray-400 text-sm mt-1">Belum ada pembayaran denda yang tercatat.</p>
                            @else
                                <p class="text-gray-500 font-medium">Tidak ada data denda ditemukan.</p>
                                <p class="text-gray-400 text-sm mt-1">Belum ada peminjaman yang menghasilkan denda.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penalties->hasPages())
        <div class="px-6 py-4 bg-gray-50">
            {{ $penalties->appends(['status' => $status, 'search' => $search])->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
