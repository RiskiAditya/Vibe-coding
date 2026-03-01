@extends('layouts.app')

@section('title', 'Categories Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h2>
        <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
            Tambah Kategori
        </button>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $category->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $category->equipment_count }} item
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')" 
                                class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                    class="text-red-600 hover:text-red-900"
                                    @if($category->equipment_count > 0) disabled title="Tidak dapat menghapus kategori yang memiliki alat" @endif>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada kategori ditemukan. Buat satu untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="createModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Buat Kategori</h3>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="create_name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" id="create_name" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeCreateModal()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Buat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Kategori</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" id="edit_name" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(id, name) {
    document.getElementById('edit_name').value = name;
    document.getElementById('editForm').action = '/categories/' + id;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection
