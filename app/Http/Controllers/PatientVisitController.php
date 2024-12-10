<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientVisitStoreRequest;
use App\Models\InventoryItem;
use App\Models\InventoryItemCategory;
use App\Models\InventoryTransaction;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\PatientVisitLabTest;
use App\Models\PatientVisitLabTestInventoryItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientVisitController extends Controller
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
    public function create($patientId)
    {
        $patient = Patient::find($patientId);
        $tests = LabTest::with('category')
            ->orderBy(InventoryItemCategory::select('name')
            ->whereColumn('lab_tests.category_id','inventory_item_categories.id'),'asc')
            ->orderBy('name', 'asc')
            ->get();
        $inventoryItems = InventoryItem::orderBy('name', 'asc')->get();
        return Inertia::render('PatientVisits/Create', props: [
            'patient' => $patient,
            'tests' => $tests,
            'inventory_items' => $inventoryItems,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientVisitStoreRequest $request)
    {
        $patientVisit = new PatientVisit;
        $patientVisit->fill($request->validated());
        $patientVisit->save();

        # Attach Inventory Items to Lab Tests
        $labTestsAndItems = $request->lab_tests_and_items;
        if($labTestsAndItems) {
            foreach($labTestsAndItems as $labTest) {

                # Create new patient visit lab test
                $patientVisitLabTest = new PatientVisitLabTest;
                $patientVisitLabTest->fill([
                    'patient_visit_id' => $patientVisit->id,
                    'lab_test_id' => $labTest['id'],
                ]);
                $patientVisitLabTest->save();

                # Attach items to specific lab test
                foreach($labTest['items'] as $item) {
                    PatientVisitLabTestInventoryItem::create([
                        'visit_lab_test_id' => $patientVisitLabTest->id,
                        'inventory_item_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    # Create new transaciton for inventory item
                    $transaction = new InventoryTransaction;
                    $transaction->inventory_item_id = $item['id'];
                    $transaction->quantity = $item['quantity'];
                    $transaction->patient_visit_id = $patientVisit->id;
                    $transaction->transaction_type = 'DECREASE';

                    $inventoryItem = InventoryItem::find($item['id']);
                    $currentStock = $inventoryItem->current_stock;
                    $newStock = $currentStock - $item['quantity'];
                    $transaction->stock = $newStock;
                    $transaction->save();
                }

            }
        }

        return redirect()->route('patients.show', $patientVisit->patient_id);
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
