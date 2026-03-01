<?php

namespace App\Services;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EquipmentService
{
    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Create new equipment.
     */
    public function createEquipment(array $data): Equipment
    {
        if (isset($data['image'])) {
            $data['image'] = $this->imageUploadService->uploadImage($data['image']);
        }

        // Set available_stock same as stock initially
        if (isset($data['stock'])) {
            $data['available_stock'] = $data['stock'];
        }

        return Equipment::create($data);
    }

    /**
     * Update existing equipment.
     */
    public function updateEquipment(Equipment $equipment, array $data): Equipment
    {
        if (isset($data['image'])) {
            // Delete old image if exists
            if ($equipment->image) {
                $this->imageUploadService->deleteImage($equipment->image);
            }
            $data['image'] = $this->imageUploadService->uploadImage($data['image']);
        }

        // Update available_stock if stock is changed
        if (isset($data['stock'])) {
            $stockDifference = $data['stock'] - $equipment->stock;
            $data['available_stock'] = $equipment->available_stock + $stockDifference;
            
            // Ensure available_stock doesn't go negative
            if ($data['available_stock'] < 0) {
                $data['available_stock'] = 0;
            }
        }

        $equipment->update($data);
        return $equipment->fresh();
    }

    /**
     * Delete equipment.
     */
    public function deleteEquipment(Equipment $equipment): bool
    {
        if (!$this->canDeleteEquipment($equipment)) {
            throw new \Exception('Cannot delete equipment that is currently borrowed.');
        }

        // Delete image if exists
        if ($equipment->image) {
            $this->imageUploadService->deleteImage($equipment->image);
        }

        return $equipment->delete();
    }

    /**
     * Check if equipment can be deleted.
     */
    public function canDeleteEquipment(Equipment $equipment): bool
    {
        return $equipment->status !== 'borrowed' && 
               !$equipment->borrowings()->whereIn('status', ['pending', 'approved'])->exists();
    }

    /**
     * Update equipment status.
     */
    public function updateStatus(Equipment $equipment, string $status): Equipment
    {
        $equipment->update(['status' => $status]);
        return $equipment->fresh();
    }

    /**
     * Get available equipment.
     */
    public function getAvailableEquipment(): Collection
    {
        return Equipment::where('status', 'available')
            ->where('available_stock', '>', 0)
            ->with('category')
            ->orderBy('name')
            ->get();
    }

    /**
     * Search and filter equipment.
     */
    public function searchAndFilter(?string $search, ?int $categoryId, ?string $status, int $perPage = 20): LengthAwarePaginator
    {
        $query = Equipment::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('name')->paginate($perPage);
    }
}
