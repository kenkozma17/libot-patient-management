<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Http\Requests\InventoryStoreRequest;
use App\Models\InventoryItem;
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
        return Inertia::render('Inventory/Create', [
            /** Props */
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
        $item = InventoryItem::find($id);
        return Inertia::render('Inventory/Show', props: [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
