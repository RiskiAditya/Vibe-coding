@extends('layouts.app')

@section('title', 'Equipment List')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Alat</h2>
        @can('manage-equipment')
        <a href="{{ route('equipment.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
            Tambah Alat
        </a>
        @endcan
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form action="{{ route('equipment.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                <input type="text" name="search" id="search" value="{{ $search }}" 
                       placeholder="Cari berdasarkan nama atau deskripsi"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" id="category_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Equipment Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($equipment as $item)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                @else
                <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                @endif
            </div>
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $item->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $item->category->name }}</p>
                <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $item->description }}</p>
                
                <!-- Stock Information -->
                <div class="mb-3 flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-gray-700">Stock: <span class="font-semibold">{{ $item->available_stock }}/{{ $item->stock }}</span></span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        Tersedia
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('equipment.show', $item) }}" class="text-blue-600 hover:text-blue-900 text-sm">Lihat</a>
                        @can('manage-equipment')
                        <a href="{{ route('equipment.edit', $item) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                        @endcan
                        @if($item->available_stock > 0 && auth()->user()->role === 'borrower')
                        <a href="{{ route('borrowings.create', ['equipment_id' => $item->id]) }}" 
                           class="text-orange-600 hover:text-orange-900 text-sm font-semibold">Pinjam</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada alat ditemukan</h3>
            <p class="mt-1 text-sm text-gray-500">Coba sesuaikan kriteria pencarian atau filter Anda.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $equipment->links() }}
    </div>
</div>
@endsection
