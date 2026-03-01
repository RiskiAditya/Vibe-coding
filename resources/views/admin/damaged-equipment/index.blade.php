@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="text-3xl font-bold" style="color: #1f2937;">List Barang Rusak</h1>
                <p style="color: #6b7280;">Daftar alat yang dikembalikan dalam kondisi rusak</p>
            </div>
            <a href="{{ route('damaged-equipment.export', request()->all()) }}" 
               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Barang Rusak</p>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $stats['total'] }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Rusak Ringan -->
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Rusak Ringan</p>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $stats['rusak_ringan'] }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-tools" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Rusak Berat -->
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Rusak Berat</p>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $stats['rusak_berat'] }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-times-circle" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Pie Chart - Rusak Ringan vs Rusak Berat -->
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
                <i class="fas fa-chart-pie" style="color: #667eea;"></i> Distribusi Tingkat Kerusakan
            </h3>
            <canvas id="damageDistributionChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Bar Chart - Damaged by Category -->
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
                <i class="fas fa-chart-bar" style="color: #667eea;"></i> Kerusakan per Kategori
            </h3>
            <canvas id="categoryChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Line Chart - Trend -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
            <i class="fas fa-chart-line" style="color: #667eea;"></i> Tren Kerusakan (6 Bulan Terakhir)
        </h3>
        <canvas id="trendChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Filter & Search -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('damaged-equipment.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                        <i class="fas fa-search" style="color: #667eea;"></i> Cari
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Nama peminjam atau alat..."
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                </div>

                <!-- Condition Filter -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                        <i class="fas fa-filter" style="color: #667eea;"></i> Kondisi
                    </label>
                    <select 
                        name="condition"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                        <option value="">Semua Kondisi</option>
                        <option value="rusak_ringan" {{ request('condition') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ request('condition') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div style="display: flex; align-items: flex-end;">
                    <button 
                        type="submit"
                        style="width: 100%; padding: 0.625rem 1.5rem; background-color: #3b82f6; color: white; border-radius: 0.5rem; font-weight: 600; border: none; cursor: pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f9fafb;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Tanggal Kembali</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Peminjam</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Alat</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Kode</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Kondisi</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Catatan Kerusakan</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Biaya Perbaikan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($damagedItems as $item)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            {{ $item->actual_return_date ? $item->actual_return_date->format('d M Y') : '-' }}
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            <div style="font-weight: 600;">{{ $item->user->name }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ $item->user->email }}</div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            <div style="font-weight: 600;">{{ $item->equipment->name }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ $item->equipment->category->name }}</div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            <span style="font-family: monospace; background: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
                                {{ $item->equipment->code }}
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem;">
                            @if($item->return_condition == 'rusak_ringan')
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-tools"></i> Rusak Ringan
                                </span>
                            @elseif($item->return_condition == 'rusak_berat')
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    <i class="fas fa-times-circle"></i> Rusak Berat
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            @if($item->damage_notes)
                                <div style="max-width: 300px;">{{ $item->damage_notes }}</div>
                            @else
                                <span style="color: #9ca3af; font-style: italic;">Tidak ada catatan</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            @if($item->repair_cost > 0)
                                <span style="font-weight: 600; color: #dc2626;">
                                    Rp {{ number_format($item->repair_cost, 0, ',', '.') }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 3rem; text-align: center; color: #9ca3af;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Tidak ada data</p>
                            <p style="font-size: 0.875rem;">Belum ada alat yang dikembalikan dalam kondisi rusak</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($damagedItems->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
            {{ $damagedItems->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Pie Chart - Distribution
const pieCtx = document.getElementById('damageDistributionChart').getContext('2d');
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: ['Rusak Ringan', 'Rusak Berat'],
        datasets: [{
            data: [{{ $stats['rusak_ringan'] }}, {{ $stats['rusak_berat'] }}],
            backgroundColor: ['#f59e0b', '#ef4444'],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: { size: 12 }
                }
            }
        }
    }
});

// Bar Chart - Category
const barCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($categoryData->pluck('category')) !!},
        datasets: [{
            label: 'Jumlah Kerusakan',
            data: {!! json_encode($categoryData->pluck('count')) !!},
            backgroundColor: '#667eea',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Line Chart - Trend
const months = {!! json_encode($monthlyData->pluck('month')->unique()->values()) !!};
const rusakRinganData = months.map(month => {
    const item = {!! json_encode($monthlyData) !!}.find(d => d.month === month && d.return_condition === 'rusak_ringan');
    return item ? item.count : 0;
});
const rusakBeratData = months.map(month => {
    const item = {!! json_encode($monthlyData) !!}.find(d => d.month === month && d.return_condition === 'rusak_berat');
    return item ? item.count : 0;
});

const lineCtx = document.getElementById('trendChart').getContext('2d');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Rusak Ringan',
                data: rusakRinganData,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Rusak Berat',
                data: rusakBeratData,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: { size: 12 }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>
@endsection
