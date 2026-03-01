@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold" style="color: #1f2937;">Riwayat Peminjam</h1>
        <p style="color: #6b7280;">Track record dan statistik peminjaman setiap peminjam</p>
    </div>

    <!-- Search -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
        <form method="GET" action="{{ route('borrower-history.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                        <i class="fas fa-search" style="color: #667eea;"></i> Cari Peminjam
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Nama, email, atau username..."
                        style="width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.875rem;">
                </div>
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
                        <th style="padding: 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Peminjam</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Total Pinjam</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Aktif</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Selesai</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Rusak</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Hilang</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Terlambat</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Total Denda</th>
                        <th style="padding: 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowers as $borrower)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 1rem; font-size: 0.875rem; color: #374151;">
                            <div style="font-weight: 600;">{{ $borrower->name }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">{{ $borrower->email }}</div>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                                {{ $borrower->total_borrowings }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                                {{ $borrower->active_borrowings }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                                {{ $borrower->completed_borrowings }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($borrower->damaged_count > 0)
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $borrower->damaged_count }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($borrower->lost_count > 0)
                                <span style="background: #e5e7eb; color: #1f2937; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $borrower->lost_count }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($borrower->late_count > 0)
                                <span style="background: #fed7aa; color: #9a3412; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $borrower->late_count }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center; font-size: 0.875rem;">
                            @if($borrower->total_penalties > 0)
                                <span style="font-weight: 600; color: #dc2626;">
                                    Rp {{ number_format($borrower->total_penalties, 0, ',', '.') }}
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="{{ route('borrower-history.show', $borrower->id) }}" 
                               style="background: #3b82f6; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding: 3rem; text-align: center; color: #9ca3af;">
                            <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Tidak ada data</p>
                            <p style="font-size: 0.875rem;">Belum ada peminjam terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($borrowers->hasPages())
        <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
            {{ $borrowers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
