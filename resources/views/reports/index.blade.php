@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">
        <i class="fas fa-chart-bar mr-2 text-blue-600"></i>Laporan Peminjaman
    </h2>

    <!-- Filter Form -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-filter mr-2 text-blue-600"></i>Filter Laporan
        </h3>
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="generate" value="1">
            
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="far fa-calendar-alt mr-1 text-gray-400"></i>Tanggal Mulai
                </label>
                <input type="date" name="start_date" id="start_date" value="{{ $filters['start_date'] ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="far fa-calendar-alt mr-1 text-gray-400"></i>Tanggal Akhir
                </label>
                <input type="date" name="end_date" id="end_date" value="{{ $filters['end_date'] ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-info-circle mr-1 text-gray-400"></i>Status
                </label>
                <select name="status" id="status" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Tertunda</option>
                    <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="returned" {{ ($filters['status'] ?? '') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>
            
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-user mr-1 text-gray-400"></i>Pengguna
                </label>
                <select name="user_id" id="user_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>
                        {{ $user->username }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-4 flex justify-end space-x-3 mt-2">
                <button type="submit" class="gradient-primary text-white px-6 py-2.5 rounded-lg font-medium hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-search mr-2"></i>Cari Laporan
                </button>
                <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg font-medium hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </a>
                @if($borrowings->count() > 0)
                <a href="{{ route('reports.print', $filters) }}" target="_blank" 
                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-medium hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-print mr-2"></i>Cetak Laporan
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Report Results -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 gradient-primary">
            <h3 class="text-lg font-semibold text-white">
                <i class="fas fa-file-alt mr-2"></i>Hasil Laporan
            </h3>
        </div>
        
        @if($borrowings->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disetujui Oleh</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $borrowing->user->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $borrowing->equipment->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrowing->equipment->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $borrowing->actual_return_date ? $borrowing->actual_return_date->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($borrowing->status === 'pending') bg-blue-100 text-blue-800
                                @elseif($borrowing->status === 'approved') bg-yellow-100 text-yellow-800
                                @elseif($borrowing->status === 'rejected') bg-red-100 text-red-800
                                @else bg-green-100 text-green-800
                                @endif">
                                @if($borrowing->status === 'pending') Tertunda
                                @elseif($borrowing->status === 'approved') Disetujui
                                @elseif($borrowing->status === 'rejected') Ditolak
                                @else Dikembalikan
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $borrowing->approver ? $borrowing->approver->username : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
            <p class="text-gray-500 font-medium">Tidak ada data peminjaman ditemukan.</p>
            <p class="text-gray-400 text-sm mt-1">Coba sesuaikan filter atau reset filter untuk melihat semua data.</p>
        </div>
        @endif
    </div>
</div>
@endsection
