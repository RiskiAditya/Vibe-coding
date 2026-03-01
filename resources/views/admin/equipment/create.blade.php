@extends('layouts.app')

@section('title', 'Tambah Alat')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Tambah Alat Baru</h2>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Alat *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori *</label>
                <select name="category_id" id="category_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                    <option value="">Pilih kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hidden field: status is always 'available' -->
            <input type="hidden" name="status" value="available">

            <div class="mb-4">
                <label for="stock" class="block text-sm font-medium text-gray-700">Jumlah Stock *</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', 1) }}" required min="1"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stock') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Jumlah total alat yang tersedia</p>
                @error('stock')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Alat</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/jpg,image/png"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Format yang diterima: JPG, JPEG, PNG. Ukuran maksimal: 2MB</p>
                @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Buat Alat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
