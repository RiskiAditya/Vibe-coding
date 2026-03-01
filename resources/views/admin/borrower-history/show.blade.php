@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('borrower-history.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.875rem; margin-bottom: 0.5rem; display: inline-block;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-bold" style="color: #1f2937;">Riwayat Peminjaman</h1>
        <p style="color: #6b7280;">{{ $user->name }} ({{ $user->email }})</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Borrowings -->
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Peminjaman</p>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $stats['total_borrowings'] }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clipboard-list" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Active -->
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Sedang Dipinjam</p>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $stats['active_borrowings'] }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Selesai</p>
                    <p style="font-size: 2rem; font-weight: bold;">{{ $stats['completed_borrowings'] }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Total Penalties -->
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 0.75rem; padding: 1.5rem; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Denda</p>
                    <p style="font-size: 1.25rem; font-weight: bold;">Rp {{ number_format($stats['total_penalties'], 0, ',', '.') }}</p>
                </div>
                <div style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 3.5rem; height: 3.5rem; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-money-bill-wave" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Problem Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #ef4444;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Barang Rusak</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937;">{{ $stats['damaged_count'] }}</p>
                </div>
                <i class="fas fa-tools" style="font-size: 2rem; color: #ef4444;"></i>
            </div>
        </div>

        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #6b7280;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Barang Hilang</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937;">{{ $stats['lost_count'] }}</p>
                </div>
                <i class="fas fa-question-circle" style="font-size: 2rem; color: #6b7280;"></i>
            </div>
        </div>

        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #f59e0b;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Terlambat</p>
                    <p style="font-size: 1.875rem; font-weight: bold; color: #1f2937;">{{ $stats['late_count'] }}</p>
                </div>
                <i class="fas fa-exclamation-triangle" style="font-size: 2rem; color: #f59e0b;"></i>
            </div>
        </div>
    </div>

    <!-- Chart - Monthly Trend -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">
            <i class="fas fa-chart-line" style="color: #3b82f6;"></i> Tren Peminjaman (6 Bulan Terakhir)
        </h3>
        <canvas id="trendChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Recent Borrowings Table -->
    <div style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1f2937;">
                <i class="fas fa-history" style="color: #3b82f6;"></i> Riwayat Peminjaman Terbaru
            </h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f9fafb;">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Tanggal Pinjam</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Alat</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Status</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Tanggal Kembali</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Kondisi</th>
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBorrowings as $borrowing)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            {{ $borrowing->created_at->format('d M Y') }}
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            <div style="font-weight: 600;">{{ $borrowing->equipment->name }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ $borrowing->equipment->category->name }}</div>
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem;">
                            @if($borrowing->status == 'pending')
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Pending</span>
                            @elseif($borrowing->status == 'approved')
                                <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Dipinjam</span>
                            @elseif($borrowing->status == 'returned')
                                <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Dikembalikan</span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Ditolak</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            {{ $borrowing->actual_return_date ? $borrowing->actual_return_date->format('d M Y') : '-' }}
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem;">
                            @if($borrowing->return_condition == 'baik')
                                <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Baik</span>
                            @elseif($borrowing->return_condition == 'rusak_ringan')
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Rusak Ringan</span>
                            @elseif($borrowing->return_condition == 'rusak_berat')
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Rusak Berat</span>
                            @elseif($borrowing->return_condition == 'hilang')
                                <span style="background: #e5e7eb; color: #1f2937; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">Hilang</span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            @if($borrowing->total_penalty > 0)
                                <span style="font-weight: 600; color: #dc2626;">
                                    Rp {{ number_format($borrowing->total_penalty, 0, ',', '.') }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 3rem; text-align: center; color: #9ca3af;">
                            <p style="font-size: 0.875rem;">Belum ada riwayat peminjaman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($recentBorrowings->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
            {{ $recentBorrowings->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Line Chart - Monthly Trend
const lineCtx = document.getElementById('trendChart').getContext('2d');
const months = {!! json_encode($monthlyTrend->pluck('month')) !!};
const counts = {!! json_encode($monthlyTrend->pluck('count')) !!};

new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: counts,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3b82f6',
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
