<?php

namespace App\Http\Controllers;
use App\Models\InventoryTransaction;
use Inertia\Inertia;
use App\Http\Requests\InventoryStoreRequest;
use App\Models\InventoryItem;
use App\Models\InventoryItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $inventoryItems = InventoryItem::where([
            [function ($query) use ($request) {
                if (($s = $request->search)) {
                    $query->orWhere('name', 'LIKE', '%' . $s . '%')
                        ->get();
                }
            }]
        ])
        ->with('category')
        ->orderBy('name', 'asc')
        ->paginate(config('pagination.default'))
        ->withQueryString();

        return Inertia::render('Inventory/InventoryList', [
            'inventory_items' => $inventoryItems,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = InventoryItemCategory::orderByDesc('name')->get();
        return Inertia::render('Inventory/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryStoreRequest $request)
    {
        $item = new InventoryItem;
        $item->fill($request->validated());
        $item->slug = Str::slug($item->name);
        $item->save();

        return redirect()->route('inventory.show', $item->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = InventoryItem::where('id', $id)->with('category')->first();
        $inventoryTransactions = InventoryTransaction::
            where('inventory_item_id', $id)
            ->with('patient_visit')
            ->orderBy('created_at', 'desc')
            ->paginate(config('pagination.default'))
            ->withQueryString();

        $itemLotNumbers = InventoryTransaction::where('inventory_item_id', $id)
            ->whereNotNull('lot_number')
            ->orderBy('lot_number', 'asc')
            ->pluck('lot_number')
            ->unique()
            ->toArray();

        return Inertia::render('Inventory/Show', props: [
            'item' => $item,
            'transactions' => $inventoryTransactions,
            'itemLotNumbers' => $itemLotNumbers,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = InventoryItem::Find($id);
        $categories = InventoryItemCategory::orderByDesc('name')->get();
        return Inertia::render('Inventory/Edit', [
            'item' => $item,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InventoryStoreRequest $request, string $id)
    {
        $item = InventoryItem::find($id);
        $item->update($request->validated());
        $item->slug = Str::slug($item->name);
        $item->save();

        return redirect()->route('inventory.show', $item->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        InventoryItem::destroy($id);
        return redirect()->route('inventory.index');
    }
}
