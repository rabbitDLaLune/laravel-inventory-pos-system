<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display all suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);

        return view('purchase.suppliers.index', compact('suppliers'));
    }

    /**
     * Show create supplier form.
     */
    public function create()
    {
        return view('purchase.suppliers.create');
    }

    /**
     * Store new supplier.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable'],
        ]);

        $data['payment_terms'] = $data['payment_terms'] ?? 'cash';
        $data['is_active'] = $request->boolean('is_active');

        Supplier::create($data);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display supplier details.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['products', 'purchaseOrders']);

        return view('purchase.suppliers.show', compact('supplier'));
    }

    /**
     * Show edit supplier form.
     */
    public function edit(Supplier $supplier)
    {
        return view('purchase.suppliers.edit', compact('supplier'));
    }

    /**
     * Update supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable'],
        ]);

        $data['payment_terms'] = $data['payment_terms'] ?? 'cash';
        $data['is_active'] = $request->boolean('is_active');

        $supplier->update($data);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Delete supplier.
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->exists() || $supplier->purchaseOrders()->exists()) {
            return back()->withErrors('This supplier cannot be deleted because it has related products or purchase orders.');
        }

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
