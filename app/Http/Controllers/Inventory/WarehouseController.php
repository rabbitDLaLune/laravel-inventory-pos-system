<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    /**
     * Display all warehouses.
     */
    public function index()
    {
        $warehouses = Warehouse::latest()->paginate(10);

        return view('inventory.warehouses.index', compact('warehouses'));
    }

    /**
     * Show create warehouse form.
     */
    public function create()
    {
        return view('inventory.warehouses.create');
    }

    /**
     * Store new warehouse.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:warehouses,code'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Warehouse::create($data);

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display warehouse details.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['sales', 'inventoryMovements', 'purchaseOrders']);

        return view('inventory.warehouses.show', compact('warehouse'));
    }

    /**
     * Show edit warehouse form.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('inventory.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update warehouse.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('warehouses', 'code')->ignore($warehouse->id),
            ],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $warehouse->update($data);

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Delete warehouse.
     */
    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->sales()->exists() || $warehouse->inventoryMovements()->exists()) {
            return back()->withErrors('This warehouse cannot be deleted because it already has related records.');
        }

        $warehouse->delete();

        return redirect()
            ->route('warehouses.index')
            ->with('success', 'Warehouse deleted successfully.');
    }
}
