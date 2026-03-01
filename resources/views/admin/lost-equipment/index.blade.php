@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="text-3xl font-bold" style="color: #1f2937;">List Barang Hilang</h1>
                <p style="color: #6b7280;">Daftar alat yang dilaporkan hilang oleh peminjam</p>
            </div>
            <a href="{{ route('lost-equipment.export', request()->all()) }}" 
               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Total Lost -->
        <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Barang Hilang</p>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $stats['total'] }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-question-circle" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Total Cost -->
        <div style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Biaya Penggantian</p>
                    <p style="font-size: 2rem; font-weight: bold;">Rp {{ number_format($stats['total_cost'], 0, ',', '.') }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-money-bill-wave" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Donut Chart - Lost by Category -->
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
                <i class="fas fa-chart-pie" style="color: #6b7280;"></i> Barang Hilang per Kategori
            </h3>
            <canvas id="categoryChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Line Chart - Trend -->
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
                <i class="fas fa-chart-line" style="color: #6b7280;"></i> Tren Kehilangan (6 Bulan Terakhir)
            </h3>
            <canvas id="trendChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Filter & Search -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('lost-equipment.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Search -->
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                        <i class="fas fa-search" style="color: #6b7280;"></i> Cari
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Nama peminjam atau alat..."
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
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
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Tanggal Laporan</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Peminjam</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Alat</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Kode</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Catatan</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Biaya Penggantian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lostItems as $item)
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
                        <td colspan="6" style="padding: 3rem; text-align: center; color: #9ca3af;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Tidak ada data</p>
                            <p style="font-size: 0.875rem;">Belum ada alat yang dilaporkan hilang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lostItems->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
            {{ $lostItems->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Donut Chart - Category
const donutCtx = document.getElementById('categoryChart').getContext('2d');
const categoryLabels = {!! json_encode($categoryData->pluck('category')) !!};
const categoryValues = {!! json_encode($categoryData->pluck('count')) !!};
const colors = ['#6b7280', '#4b5563', '#374151', '#1f2937', '#9ca3af', '#d1d5db'];

new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryValues,
            backgroundColor: colors.slice(0, categoryLabels.length),
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

// Line Chart - Trend
const lineCtx = document.getElementById('trendChart').getContext('2d');
const months = {!! json_encode($monthlyData->pluck('month')) !!};
const counts = {!! json_encode($monthlyData->pluck('count')) !!};

new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Barang Hilang',
            data: counts,
            borderColor: '#6b7280',
            backgroundColor: 'rgba(107, 114, 128, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#6b7280',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
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
