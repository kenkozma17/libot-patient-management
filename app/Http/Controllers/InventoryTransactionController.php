<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryTransactionStoreRequest;
use App\Models\InventoryTransaction;
use App\Models\InventoryItem;
use App\Notifications\ExpirationNotice;
use App\Notifications\LowInventory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryTransactionStoreRequest $request)
    {
        $transaction = new InventoryTransaction;
        $transaction->fill($request->validated());

        // Calculate Stock
        $item = InventoryItem::find($transaction->inventory_item_id);
        $currentStock = $item->current_stock;
        if($transaction->transaction_type === 'INCREASE') {
            $newStock = $currentStock + $transaction->quantity;
            $transaction->stock = $newStock;
        } elseif($transaction->transaction_type === 'DECREASE') {
            $newStock = $currentStock - $transaction->quantity;
            $transaction->stock = $newStock;
        }
        $transaction->save();

        # Create notification if low stock level is met
        if($newStock <= $item->low_stock_limit) {
            $item->notify(new LowInventory($item));
        }

        return redirect()->route('inventory.show', $transaction->inventory_item_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = InventoryTransaction::with(['inventory_item'])->find($id);

        return Inertia::render('InventoryTransaction/Show', props: [
            'transaction' => $transaction,
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
