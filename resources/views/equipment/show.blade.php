@extends('layouts.app')

@section('title', $equipment->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                <div class="h-96 bg-gray-200 flex items-center justify-center">
                    @if($equipment->image)
                    <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->name }}" class="h-full w-full object-cover">
                    @else
                    <svg class="h-32 w-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    @endif
                </div>
            </div>
            <div class="md:w-1/2 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $equipment->name }}</h2>
                
                <div class="mb-4">
                    <span class="text-sm text-gray-600">Category:</span>
                    <span class="ml-2 text-sm font-semibold text-gray-900">{{ $equipment->category->name }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="ml-2 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        Tersedia
                    </span>
                </div>

                <div class="mb-4">
                    <span class="text-sm text-gray-600">Stock:</span>
                    <span class="ml-2 text-sm font-semibold text-gray-900">
                        {{ $equipment->available_stock }} tersedia dari {{ $equipment->stock }} total
                    </span>
                </div>

                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Description</h3>
                    <p class="text-sm text-gray-600">{{ $equipment->description ?: 'No description available.' }}</p>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Back to List
                    </a>
                    @can('manage-equipment')
                    <a href="{{ route('equipment.edit', $equipment) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                        Edit
                    </a>
                    @endcan
                    @if($equipment->available_stock > 0 && auth()->user()->role === 'borrower')
                    <a href="{{ route('borrowings.create', ['equipment_id' => $equipment->id]) }}" 
                       class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                        Pinjam Alat Ini
                    </a>
                    @endif
                </div>
            </div>
        </div>

        @if($equipment->borrowings->count() > 0)
        <div class="border-t border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Borrowing History</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Borrower</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Borrow Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Return Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($equipment->borrowings->take(5) as $borrowing)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $borrowing->user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $borrowing->borrow_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $borrowing->actual_return_date ? $borrowing->actual_return_date->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($borrowing->status === 'approved') bg-yellow-100 text-yellow-800
                                    @elseif($borrowing->status === 'returned') bg-green-100 text-green-800
                                    @elseif($borrowing->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
