@extends('layouts.app')

@section('title', 'Pengembalian Alat')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">
        <i class="fas fa-undo-alt mr-2 text-blue-600"></i>Pengembalian Alat
    </h2>

    <!-- Borrowing Details Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 shadow-md rounded-lg p-6 mb-6 border border-blue-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2 text-blue-600"></i>Detail Peminjaman
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start">
                <i class="fas fa-box text-gray-400 mt-1 mr-3"></i>
                <div>
                    <span class="text-sm text-gray-600 block">Alat:</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $borrowing->equipment->name }}</span>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-tag text-gray-400 mt-1 mr-3"></i>
                <div>
                    <span class="text-sm text-gray-600 block">Kategori:</span>
                    <span class="text-sm text-gray-900">{{ $borrowing->equipment->category->name }}</span>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-calendar-check text-gray-400 mt-1 mr-3"></i>
                <div>
                    <span class="text-sm text-gray-600 block">Tanggal Pinjam:</span>
                    <span class="text-sm text-gray-900">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-calendar-times text-gray-400 mt-1 mr-3"></i>
                <div>
                    <span class="text-sm text-gray-600 block">Tanggal Kembali:</span>
                    <span class="text-sm text-gray-900">{{ $borrowing->requested_return_date->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Late Warning -->
        @php
            $lateDays = $borrowing->calculateLateDays();
            $lateFee = $borrowing->calculateLateFee();
        @endphp
        @if($lateDays > 0)
        <div class="mt-4 bg-red-100 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                <div>
                    <p class="text-red-800 font-semibold">Terlambat {{ $lateDays }} hari!</p>
                    <p class="text-red-700 text-sm">Denda keterlambatan: Rp {{ number_format($lateFee, 0, ',', '.') }} (Rp 5.000/hari)</p>
                </div>
            </div>
        </div>
        @else
        <div class="mt-4 bg-green-100 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-green-800 font-semibold">Pengembalian tepat waktu - Tidak ada denda keterlambatan</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Return Condition Form -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-clipboard-check mr-2 text-orange-600"></i>Pengecekan Kondisi Barang
        </h3>
        
        <form action="{{ route('returns.store', $borrowing) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Return Condition -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-star text-yellow-500 mr-1"></i>Kondisi Barang Saat Dikembalikan *
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-green-50 transition-colors" onclick="toggleCondition('baik')">
                        <input type="radio" name="return_condition" value="baik" class="mr-3" required {{ old('return_condition') == 'baik' ? 'checked' : '' }}>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                            <div>
                                <span class="font-semibold text-gray-900 block">Baik</span>
                                <span class="text-xs text-gray-600">Tidak ada kerusakan</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-yellow-50 transition-colors" onclick="toggleCondition('rusak_ringan')">
                        <input type="radio" name="return_condition" value="rusak_ringan" class="mr-3" {{ old('return_condition') == 'rusak_ringan' ? 'checked' : '' }}>
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-yellow-500 text-2xl mr-3"></i>
                            <div>
                                <span class="font-semibold text-gray-900 block">Rusak Ringan</span>
                                <span class="text-xs text-gray-600">Goresan, lecet kecil</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-orange-50 transition-colors" onclick="toggleCondition('rusak_berat')">
                        <input type="radio" name="return_condition" value="rusak_berat" class="mr-3" {{ old('return_condition') == 'rusak_berat' ? 'checked' : '' }}>
                        <div class="flex items-center">
                            <i class="fas fa-times-circle text-orange-500 text-2xl mr-3"></i>
                            <div>
                                <span class="font-semibold text-gray-900 block">Rusak Berat</span>
                                <span class="text-xs text-gray-600">Tidak berfungsi, rusak parah</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-red-50 transition-colors" onclick="toggleCondition('hilang')">
                        <input type="radio" name="return_condition" value="hilang" class="mr-3" {{ old('return_condition') == 'hilang' ? 'checked' : '' }}>
                        <div class="flex items-center">
                            <i class="fas fa-ban text-red-500 text-2xl mr-3"></i>
                            <div>
                                <span class="font-semibold text-gray-900 block">Hilang</span>
                                <span class="text-xs text-gray-600">Barang tidak dapat dikembalikan</span>
                            </div>
                        </div>
                    </label>
                </div>
                @error('return_condition')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Damage Notes (shown when not 'baik') -->
            <div id="damageSection" style="display: none;">
                <label for="damage_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-file-alt mr-1 text-gray-500"></i>Catatan Kerusakan
                </label>
                <textarea 
                    name="damage_notes" 
                    id="damage_notes" 
                    rows="4"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                    placeholder="Jelaskan kondisi kerusakan secara detail...">{{ old('damage_notes') }}</textarea>
                @error('damage_notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Repair Cost (shown when not 'baik') -->
            <div id="costSection" style="display: none;">
                <label for="repair_cost" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-money-bill-wave mr-1 text-green-500"></i>Biaya Perbaikan/Penggantian (Rp)
                </label>
                <input 
                    type="number" 
                    name="repair_cost" 
                    id="repair_cost" 
                    value="{{ old('repair_cost', 0) }}"
                    min="0"
                    step="1000"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                    placeholder="0">
                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada biaya perbaikan</p>
                @error('repair_cost')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Summary -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-calculator mr-2 text-blue-600"></i>Ringkasan Penalti
                </h4>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Denda Keterlambatan:</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format($lateFee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya Perbaikan:</span>
                        <span class="font-semibold text-gray-900" id="displayRepairCost">Rp 0</span>
                    </div>
                    <div class="border-t border-gray-300 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900">Total Penalti:</span>
                            <span class="font-bold text-red-600 text-lg" id="displayTotalPenalty">Rp {{ number_format($lateFee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('borrowings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2.5 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors shadow-lg">
                    <i class="fas fa-check mr-2"></i>Proses Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const lateFee = {{ $lateFee }};
    
    function toggleCondition(condition) {
        const damageSection = document.getElementById('damageSection');
        const costSection = document.getElementById('costSection');
        
        if (condition === 'baik') {
            damageSection.style.display = 'none';
            costSection.style.display = 'none';
            document.getElementById('damage_notes').value = '';
            document.getElementById('repair_cost').value = 0;
        } else {
            damageSection.style.display = 'block';
            costSection.style.display = 'block';
        }
        
        updateTotalPenalty();
    }
    
    function updateTotalPenalty() {
        const repairCost = parseFloat(document.getElementById('repair_cost').value) || 0;
        const totalPenalty = lateFee + repairCost;
        
        document.getElementById('displayRepairCost').textContent = 'Rp ' + repairCost.toLocaleString('id-ID');
        document.getElementById('displayTotalPenalty').textContent = 'Rp ' + totalPenalty.toLocaleString('id-ID');
    }
    
    document.getElementById('repair_cost').addEventListener('input', updateTotalPenalty);
    
    // Check initial state
    document.addEventListener('DOMContentLoaded', function() {
        const checkedRadio = document.querySelector('input[name="return_condition"]:checked');
        if (checkedRadio && checkedRadio.value !== 'baik') {
            toggleCondition(checkedRadio.value);
        }
    });
</script>
@endsection
