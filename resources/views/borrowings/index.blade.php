@extends('layouts.app')

@section('title', 'Borrowings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">
        @if(auth()->user()->role === 'borrower')
            Peminjaman Saya
        @else
            Semua Permintaan Peminjaman
        @endif
    </h2>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @if(auth()->user()->role !== 'borrower')
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($borrowings as $borrowing)
                <tr class="{{ $borrowing->isLate() ? 'bg-red-50' : '' }}">
                    @if(auth()->user()->role !== 'borrower')
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $borrowing->user->username }}
                    </td>
                    @endif
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="font-medium">{{ $borrowing->equipment->name }}</div>
                        @if($borrowing->status === 'returned' && $borrowing->return_condition)
                            <div class="text-xs mt-1">
                                <span class="px-2 py-0.5 rounded-full
                                    @if($borrowing->return_condition === 'baik') bg-green-100 text-green-800
                                    @elseif($borrowing->return_condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                    @elseif($borrowing->return_condition === 'rusak_berat') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $borrowing->return_condition)) }}
                                </span>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $borrowing->equipment->category->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $borrowing->borrow_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <div>
                            @if($borrowing->actual_return_date)
                                <span class="font-medium">{{ $borrowing->actual_return_date->format('d M Y') }}</span>
                            @else
                                {{ $borrowing->requested_return_date->format('d M Y') }}
                            @endif
                        </div>
                        @if($borrowing->status === 'approved' && $borrowing->isLate())
                            <div class="text-xs text-red-600 font-semibold mt-1">
                                <i class="fas fa-exclamation-triangle"></i> Terlambat {{ $borrowing->calculateLateDays() }} hari
                            </div>
                        @endif
                        @if($borrowing->status === 'returned' && $borrowing->late_days > 0)
                            <div class="text-xs text-red-600 mt-1">
                                Terlambat {{ $borrowing->late_days }} hari
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($borrowing->status === 'pending') bg-blue-100 text-blue-800
                                @elseif($borrowing->status === 'approved') bg-yellow-100 text-yellow-800
                                @elseif($borrowing->status === 'rejected') bg-red-100 text-red-800
                                @else bg-green-100 text-green-800
                                @endif">
                                @if($borrowing->status === 'pending') Menunggu
                                @elseif($borrowing->status === 'approved') Disetujui
                                @elseif($borrowing->status === 'rejected') Ditolak
                                @else Dikembalikan
                                @endif
                            </span>
                        </div>
                        @if($borrowing->status === 'returned' && $borrowing->total_penalty > 0)
                            <div class="text-xs text-red-600 font-semibold mt-1">
                                Penalti: Rp {{ number_format($borrowing->total_penalty, 0, ',', '.') }}
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($borrowing->status === 'pending' && (auth()->user()->role === 'admin' || auth()->user()->role === 'staff'))
                            <form action="{{ route('borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Setujui</button>
                            </form>
                            <form action="{{ route('borrowings.reject', $borrowing) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-900">Tolak</button>
                            </form>
                        @elseif($borrowing->status === 'approved')
                            @if($borrowing->user_id === auth()->id() || in_array(auth()->user()->role, ['admin', 'staff']))
                                <a href="{{ route('returns.create', $borrowing) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-undo-alt mr-1"></i>Kembalikan
                                </a>
                            @else
                                -
                            @endif
                        @elseif($borrowing->status === 'returned')
                            <button onclick="showReturnDetail({{ $borrowing->id }})" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-info-circle mr-1"></i>Detail
                            </button>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada catatan peminjaman ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($borrowings, 'links'))
    <div class="mt-6">
        {{ $borrowings->links() }}
    </div>
    @endif
</div>

<!-- Return Detail Modal -->
<div id="returnDetailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">
                <i class="fas fa-clipboard-check mr-2 text-blue-600"></i>Detail Pengembalian
            </h3>
            <button onclick="closeReturnDetail()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div id="returnDetailContent" class="space-y-4">
            <!-- Content will be loaded dynamically -->
        </div>
    </div>
</div>

<script>
    const borrowingsData = @json($borrowings->items());
    
    function showReturnDetail(borrowingId) {
        const borrowing = borrowingsData.find(b => b.id === borrowingId);
        if (!borrowing) return;
        
        const conditionLabels = {
            'baik': 'Baik',
            'rusak_ringan': 'Rusak Ringan',
            'rusak_berat': 'Rusak Berat',
            'hilang': 'Hilang'
        };
        
        const conditionColors = {
            'baik': 'bg-green-100 text-green-800',
            'rusak_ringan': 'bg-yellow-100 text-yellow-800',
            'rusak_berat': 'bg-orange-100 text-orange-800',
            'hilang': 'bg-red-100 text-red-800'
        };
        
        let content = `
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Alat:</span>
                    <span class="text-sm font-semibold text-gray-900">${borrowing.equipment.name}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Kondisi Pengembalian:</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full ${conditionColors[borrowing.return_condition] || 'bg-gray-100 text-gray-800'}">
                        ${conditionLabels[borrowing.return_condition] || '-'}
                    </span>
                </div>
        `;
        
        if (borrowing.damage_notes) {
            content += `
                <div class="border-t pt-3">
                    <span class="text-sm text-gray-600 block mb-2">Catatan Kerusakan:</span>
                    <p class="text-sm text-gray-900 bg-white p-3 rounded border">${borrowing.damage_notes}</p>
                </div>
            `;
        }
        
        content += `
                <div class="border-t pt-3">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Hari Terlambat:</span>
                        <span class="text-sm font-semibold ${borrowing.late_days > 0 ? 'text-red-600' : 'text-green-600'}">
                            ${borrowing.late_days} hari
                        </span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Denda Keterlambatan:</span>
                        <span class="text-sm font-semibold text-gray-900">Rp ${Number(borrowing.late_fee).toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Biaya Perbaikan:</span>
                        <span class="text-sm font-semibold text-gray-900">Rp ${Number(borrowing.repair_cost).toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="text-base font-bold text-gray-900">Total Penalti:</span>
                        <span class="text-lg font-bold text-red-600">Rp ${Number(borrowing.total_penalty).toLocaleString('id-ID')}</span>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('returnDetailContent').innerHTML = content;
        document.getElementById('returnDetailModal').classList.remove('hidden');
    }
    
    function closeReturnDetail() {
        document.getElementById('returnDetailModal').classList.add('hidden');
    }
</script>
@endsection
