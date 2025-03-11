<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Patient;
use App\Notifications\ExpirationNotice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkForExpiration();
        $this->resetMemberCredits();
        return Inertia::render('Home');
    }

    public function resetMemberCredits() {
        $currentYear = Carbon::now()->year;

        $members = Patient::where('is_member', '=', 1)->get();
        if($members->isNotEmpty()) {
            if($members[0]->credit_reset_at != $currentYear) {
                foreach($members as $member) {
                    $member->update(['credit_reset_at' => $currentYear, 'credits' => '5000']);
                    $member->save();
                }
            }
        }
    }

    public function checkForExpiration () {
        $inventoryItems = InventoryItem::where('expiry_check_date', '!=', Carbon::today())
        ->where('days_before_expiration_limit', '>', 0)
        ->with(['transactions' => function($query) {
            $query->whereNotNull('expiration_date')->where('is_archived', '=', 0);
        }])->get();

        foreach($inventoryItems as $item) {
            $today = Carbon::now();
            foreach($item->transactions as $transaction) {
                $expirationDate = Carbon::parse($transaction->expiration_date);
                $isTodayBeforeExpiration = $today->isBefore($expirationDate);
                $isTodayWithinExp = $today->diffInDays($expirationDate) <= $item->days_before_expiration_limit;

                if($isTodayWithinExp || $isTodayBeforeExpiration === false) {
                    $item->notify(new ExpirationNotice($item, $transaction->lot_number, $isTodayBeforeExpiration));
                    $item->update(['expiry_check_date' => Carbon::now()]);
                    $item->save();
                }
            }
        }
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
    public function store(Request $request)
    {
        //
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
