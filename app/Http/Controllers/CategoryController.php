<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = $this->categoryService->getCategoriesWithCounts();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        try {
            $this->categoryService->createCategory($request->name);
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create category: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        try {
            $this->categoryService->updateCategory($category, $request->name);
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($category);
            
            return redirect()
                ->route('categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
