@extends('layouts.app')

@section('title', 'Borrow Equipment')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Borrow Equipment</h2>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipment Details</h3>
        <div class="flex items-start space-x-4">
            <div class="h-24 w-24 bg-gray-200 rounded-md flex items-center justify-center flex-shrink-0">
                @if($equipment->image)
                <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->name }}" class="h-full w-full object-cover rounded-md">
                @else
                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                @endif
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900">{{ $equipment->name }}</h4>
                <p class="text-sm text-gray-600">{{ $equipment->category->name }}</p>
                <p class="text-sm text-gray-500 mt-2">{{ $equipment->description }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Borrowing Request</h3>
        <form action="{{ route('borrowings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

            <div class="mb-4">
                <label for="requested_return_date" class="block text-sm font-medium text-gray-700">Requested Return Date *</label>
                <input type="date" name="requested_return_date" id="requested_return_date" 
                       value="{{ old('requested_return_date') }}" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('requested_return_date') border-red-500 @enderror">
                @error('requested_return_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Add any special requests or notes about your borrowing.</p>
                @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
