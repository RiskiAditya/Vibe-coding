@extends('layouts.app')

@section('title', 'Pending Returns')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Pengembalian Tertunda</h2>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permintaan Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($borrowings as $borrowing)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $borrowing->user->username }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $borrowing->equipment->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $borrowing->borrow_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $borrowing->requested_return_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openApproveModal({{ $borrowing->id }}, '{{ $borrowing->equipment->name }}')" 
                                class="text-green-600 hover:text-green-900">
                            Setujui Pengembalian
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada pengembalian tertunda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Approve Return Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui Pengembalian</h3>
        <p class="text-sm text-gray-600 mb-4">Alat: <span id="equipmentName" class="font-semibold"></span></p>
        <form id="approveForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="equipment_condition" class="block text-sm font-medium text-gray-700">Kondisi Alat *</label>
                <select name="equipment_condition" id="equipment_condition" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="available">Kondisi Baik (Tersedia)</option>
                    <option value="damaged">Rusak</option>
                    <option value="maintenance">Perlu Pemeliharaan</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeApproveModal()" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Batal
                </button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    Setujui Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(borrowingId, equipmentName) {
    document.getElementById('equipmentName').textContent = equipmentName;
    document.getElementById('approveForm').action = '/returns/' + borrowingId + '/approve';
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}
</script>
@endsection
