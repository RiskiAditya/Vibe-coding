@extends('layouts.app')

@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard Staff')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat datang kembali, {{ auth()->user()->username }}!</h2>
        <p class="text-gray-600">Berikut yang terjadi dengan sistem peminjaman alat Anda hari ini.</p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-primary-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>Akun aktif
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full gradient-primary flex items-center justify-center">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Alat</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_equipment'] }}</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-box mr-1"></i>Dalam inventaris
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full gradient-success flex items-center justify-center">
                    <i class="fas fa-box text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Peminjaman Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['active_borrowings'] }}</p>
                    <p class="text-xs text-yellow-600 mt-2">
                        <i class="fas fa-clock mr-1"></i>Sedang dipinjam
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-accent-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Permintaan Tertunda</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_requests'] }}</p>
                    <p class="text-xs text-orange-600 mt-2">
                        <i class="fas fa-bell mr-1"></i>Perlu perhatian
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full gradient-accent flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Penalty Statistics Card -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 mb-8 text-white">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-bold mb-1">Statistik Denda</h3>
                <p class="text-red-100 text-sm">Ringkasan pembayaran denda peminjaman</p>
            </div>
            <a href="{{ route('penalties.index') }}" class="bg-white text-red-600 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors font-semibold text-sm">
                <i class="fas fa-arrow-right mr-2"></i>Kelola Denda
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <p class="text-red-100 text-xs mb-1">Total Denda</p>
                <p class="text-2xl font-bold">Rp {{ number_format($penaltyStats['total_penalties'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <p class="text-red-100 text-xs mb-1">Belum Dibayar</p>
                <p class="text-2xl font-bold">Rp {{ number_format($penaltyStats['unpaid_penalties'], 0, ',', '.') }}</p>
                <p class="text-xs text-red-100 mt-1">{{ $penaltyStats['unpaid_count'] }} transaksi</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <p class="text-red-100 text-xs mb-1">Sudah Dibayar</p>
                <p class="text-2xl font-bold">Rp {{ number_format($penaltyStats['paid_penalties'], 0, ',', '.') }}</p>
                <p class="text-xs text-red-100 mt-1">{{ $penaltyStats['paid_count'] }} transaksi</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                <p class="text-red-100 text-xs mb-1">Tingkat Pembayaran</p>
                <p class="text-2xl font-bold">
                    {{ $penaltyStats['total_count'] > 0 ? number_format(($penaltyStats['paid_count'] / $penaltyStats['total_count']) * 100, 1) : 0 }}%
                </p>
                <p class="text-xs text-red-100 mt-1">Dari total denda</p>
            </div>
        </div>
    </div>

    <!-- New Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Line Chart - Monthly Borrowing Trends -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Tren Peminjaman Bulanan</h3>
                <i class="fas fa-chart-line text-primary-500"></i>
            </div>
            <canvas id="monthlyTrendChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Pie Chart - Equipment by Category -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Alat per Kategori</h3>
                <i class="fas fa-chart-pie text-primary-500"></i>
            </div>
            <canvas id="categoryPieChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Bar Chart - Most Borrowed Equipment -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">10 Alat Paling Sering Dipinjam</h3>
            <i class="fas fa-chart-bar text-primary-500"></i>
        </div>
        <canvas id="mostBorrowedChart" style="max-height: 300px;"></canvas>
    </div>

    <!-- Donut Chart - Borrowing Status Distribution -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 card-hover">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Distribusi Status Peminjaman</h3>
            <i class="fas fa-chart-pie text-primary-500"></i>
        </div>
        <div style="max-width: 400px; margin: 0 auto;">
            <canvas id="borrowingStatusChart"></canvas>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Equipment Status Distribution -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Status Alat</h3>
                <i class="fas fa-chart-pie text-primary-500"></i>
            </div>
            <div class="space-y-4">
                @foreach($statusDistribution as $status => $count)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-sm font-medium text-gray-700">{{ ucfirst($status) }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full transition-all duration-500 bg-green-500" 
                            style="width: {{ $stats['total_equipment'] > 0 ? ($count / $stats['total_equipment'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Category Statistics -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Alat per Kategori</h3>
                <i class="fas fa-tags text-primary-500"></i>
            </div>
            <div class="space-y-3">
                @foreach($categoryStats as $stat)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg gradient-primary flex items-center justify-center">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ $stat['name'] }}</span>
                    </div>
                    <span class="px-4 py-1.5 bg-primary-100 text-primary-800 rounded-full text-sm font-bold">
                        {{ $stat['count'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Equipment Utilization -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
        <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-chart-line mr-2"></i>
                Utilisasi Alat per Kategori
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Total Alat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Sedang Dipinjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tingkat Utilisasi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($equipmentUtilization as $util)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg gradient-primary flex items-center justify-center mr-3">
                                    <i class="fas fa-box text-white text-sm"></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $util['category'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">{{ $util['total'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">{{ $util['borrowed'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[100px]">
                                    <div class="h-2 rounded-full transition-all duration-500
                                        @if($util['rate'] >= 75) bg-red-500
                                        @elseif($util['rate'] >= 50) bg-yellow-500
                                        @else bg-green-500
                                        @endif" 
                                        style="width: {{ $util['rate'] }}%"></div>
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $util['rate'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Line Chart - Monthly Borrowing Trends
const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
new Chart(monthlyTrendCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyTrends['labels']) !!},
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: {!! json_encode($monthlyTrends['values']) !!},
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
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

// Pie Chart - Equipment by Category
const categoryPieCtx = document.getElementById('categoryPieChart').getContext('2d');
new Chart(categoryPieCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($equipmentByCategory['labels']) !!},
        datasets: [{
            data: {!! json_encode($equipmentByCategory['values']) !!},
            backgroundColor: [
                '#3b82f6',
                '#10b981',
                '#f59e0b',
                '#ef4444',
                '#8b5cf6',
                '#ec4899',
                '#14b8a6',
                '#f97316'
            ],
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

// Bar Chart - Most Borrowed Equipment
const mostBorrowedCtx = document.getElementById('mostBorrowedChart').getContext('2d');
new Chart(mostBorrowedCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($mostBorrowed['labels']) !!},
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: {!! json_encode($mostBorrowed['values']) !!},
            backgroundColor: '#3b82f6',
            borderRadius: 6,
            barThickness: 40
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

// Donut Chart - Borrowing Status Distribution
const borrowingStatusCtx = document.getElementById('borrowingStatusChart').getContext('2d');
const statusLabels = [];
const statusValues = [];
const statusColors = [];
const statusData = {!! json_encode($borrowingStatus) !!};

// Map status to Indonesian labels and colors
const statusMap = {
    'pending': { label: 'Pending', color: '#f59e0b' },
    'approved': { label: 'Dipinjam', color: '#3b82f6' },
    'returned': { label: 'Dikembalikan', color: '#10b981' },
    'rejected': { label: 'Ditolak', color: '#ef4444' }
};

for (const [status, count] of Object.entries(statusData)) {
    if (statusMap[status]) {
        statusLabels.push(statusMap[status].label);
        statusValues.push(count);
        statusColors.push(statusMap[status].color);
    }
}

new Chart(borrowingStatusCtx, {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusValues,
            backgroundColor: statusColors,
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
</script>
@endsection
