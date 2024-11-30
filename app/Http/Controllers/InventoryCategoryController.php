<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryCategoryStoreRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InventoryItemCategory;
use Illuminate\Support\Str;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = InventoryItemCategory::orderByDesc('name')
            ->paginate(config('pagination.default'));

        return Inertia::render('InventoryCategory/InventoryCategoryList', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('InventoryCategory/Create', [
            /** Props */
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryCategoryStoreRequest $request)
    {
        $item = new InventoryItemCategory();
        $item->fill($request->validated());
        $item->slug = Str::slug($item->name);
        $item->save();

        return redirect()->route('inventory-categories.index', $item->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = InventoryItemCategory::Find($id);
        return Inertia::render('InventoryCategory/Edit', [
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryCategoryStoreRequest $request, string $id)
    {
        $item = InventoryItemCategory::find($id);
        $item->update($request->validated());
        $item->slug = Str::slug($item->name);
        $item->save();

        return redirect()->route('inventory-categories.index', $item->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        InventoryItemCategory::destroy($id);
        return redirect()->route('inventory-categories.index');
    }
}
