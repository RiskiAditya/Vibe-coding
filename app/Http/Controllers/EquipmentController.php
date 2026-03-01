<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use App\Services\EquipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    protected EquipmentService $equipmentService;

    public function __construct(EquipmentService $equipmentService)
    {
        $this->equipmentService = $equipmentService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of equipment.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $status = $request->get('status');

        $equipment = $this->equipmentService->searchAndFilter($search, $categoryId, $status);
        $categories = Category::orderBy('name')->get();

        return view('equipment.index', compact('equipment', 'categories', 'search', 'categoryId', 'status'));
    }

    /**
     * Show the form for creating new equipment.
     */
    public function create(): View
    {
        $this->authorize('manage-equipment');
        
        $categories = Category::orderBy('name')->get();
        return view('admin.equipment.create', compact('categories'));
    }

    /**
     * Store a newly created equipment.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('manage-equipment');

        $request->validate([
            'name' => 'required|string|min:3|max:200',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:available,borrowed,damaged,maintenance',
            'stock' => 'required|integer|min:1|max:1000',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            $this->equipmentService->createEquipment($request->all());
            
            return redirect()
                ->route('equipment.index')
                ->with('success', 'Alat berhasil dibuat.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat alat: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified equipment.
     */
    public function show(Equipment $equipment): View
    {
        $equipment->load('category', 'borrowings.user');
        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing equipment.
     */
    public function edit(Equipment $equipment): View
    {
        $this->authorize('manage-equipment');
        
        $categories = Category::orderBy('name')->get();
        return view('admin.equipment.edit', compact('equipment', 'categories'));
    }

    /**
     * Update the specified equipment.
     */
    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $this->authorize('manage-equipment');

        $request->validate([
            'name' => 'required|string|min:3|max:200',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:available,borrowed,damaged,maintenance',
            'stock' => 'required|integer|min:1|max:1000',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        try {
            $this->equipmentService->updateEquipment($equipment, $request->all());
            
            return redirect()
                ->route('equipment.index')
                ->with('success', 'Alat berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui alat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified equipment.
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        $this->authorize('manage-equipment');

        try {
            $this->equipmentService->deleteEquipment($equipment);
            
            return redirect()
                ->route('equipment.index')
                ->with('success', 'Alat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
