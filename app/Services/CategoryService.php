<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    /**
     * Create a new category.
     */
    public function createCategory(string $name): Category
    {
        return Category::create(['name' => $name]);
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Category $category, string $name): Category
    {
        $category->update(['name' => $name]);
        return $category->fresh();
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(Category $category): bool
    {
        if (!$this->canDeleteCategory($category)) {
            throw new \Exception('Cannot delete category with associated equipment.');
        }

        return $category->delete();
    }

    /**
     * Check if a category can be deleted.
     */
    public function canDeleteCategory(Category $category): bool
    {
        return $category->equipment()->count() === 0;
    }

    /**
     * Get all categories with equipment counts.
     */
    public function getCategoriesWithCounts(): Collection
    {
        return Category::withCount('equipment')
            ->orderBy('name')
            ->get();
    }
}
