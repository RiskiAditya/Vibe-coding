<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class DamagedEquipmentController extends Controller
{
    /**
     * Export damaged equipment to Excel (CSV format).
     */
    public function exportExcel(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment.category'])
            ->where('status', 'returned')
            ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat']);

        if ($request->filled('condition')) {
            $query->where('return_condition', $request->condition);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('equipment', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $items = $query->orderBy('actual_return_date', 'desc')->get();

        $filename = 'barang-rusak-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            // Header
            fputcsv($file, [
                'Tanggal Kembali',
                'Peminjam',
                'Email',
                'Alat',
                'Kode',
                'Kategori',
                'Kondisi',
                'Catatan Kerusakan',
                'Biaya Perbaikan'
            ]);

            // Data
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->actual_return_date ? $item->actual_return_date->format('d/m/Y') : '-',
                    $item->user->name,
                    $item->user->email,
                    $item->equipment->name,
                    $item->equipment->code,
                    $item->equipment->category->name,
                    $item->return_condition == 'rusak_ringan' ? 'Rusak Ringan' : 'Rusak Berat',
                    $item->damage_notes ?? '-',
                    $item->repair_cost > 0 ? 'Rp ' . number_format($item->repair_cost, 0, ',', '.') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display list of damaged equipment from returns.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment.category'])
            ->where('status', 'returned')
            ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat']);

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('return_condition', $request->condition);
        }

        // Search by user name or equipment name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('equipment', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $damagedItems = $query->orderBy('actual_return_date', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Borrowing::where('status', 'returned')
                ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat'])
                ->count(),
            'rusak_ringan' => Borrowing::where('status', 'returned')
                ->where('return_condition', 'rusak_ringan')
                ->count(),
            'rusak_berat' => Borrowing::where('status', 'returned')
                ->where('return_condition', 'rusak_berat')
                ->count(),
            'total_cost' => Borrowing::where('status', 'returned')
                ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat'])
                ->sum('repair_cost'),
        ];

        // Chart data - Damaged items by month (last 6 months)
        $monthlyData = Borrowing::where('status', 'returned')
            ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat'])
            ->whereNotNull('actual_return_date')
            ->where('actual_return_date', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(actual_return_date, "%Y-%m") as month, return_condition, COUNT(*) as count')
            ->groupBy('month', 'return_condition')
            ->orderBy('month')
            ->get();

        // Chart data - Damaged items by category
        $categoryData = Borrowing::where('borrowings.status', 'returned')
            ->whereIn('borrowings.return_condition', ['rusak_ringan', 'rusak_berat'])
            ->join('equipment', 'borrowings.equipment_id', '=', 'equipment.id')
            ->join('categories', 'equipment.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as category, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return view('admin.damaged-equipment.index', compact('damagedItems', 'stats', 'monthlyData', 'categoryData'));
    }
}
