<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientVisitStoreRequest;
use App\Models\InventoryItem;
use App\Models\InventoryItemCategory;
use App\Models\InventoryTransaction;
use App\Models\Invoice;
use App\Models\InvoiceItem;
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
    public function index(Request $request)
    {
        $search = $request->get('search');
        $visits = PatientVisit::with('patient')->whereHas('patient',
            function ($query) use ($request) {
                if (($s = $request->search)) {
                    $query->where('last_name', 'LIKE', '%' . $s . '%')
                        ->orWhere('first_name', 'LIKE', '%' . $s . '%')
                        ->orWhere('phone', 'LIKE', '%' . $s . '%');
                }
            }
        )
        ->orderBy('created_at', 'desc')
        ->paginate(config('pagination.default'))
        ->withQueryString();

        return Inertia::render('PatientVisits/PatientVisitsList', [
            'visits' => $visits,
            'search' => $search,
        ]);
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

        # Create new invoice
        $invoice = new Invoice();
        $invoice->save();

        # Create new patient visit and associate invoice
        $patientVisit = new PatientVisit;
        $patientVisit->fill($request->validated());
        $patientVisit->invoice()->associate($invoice);
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
                    'discount_percentage' => $labTest['discount_percentage']
                ]);
                $patientVisitLabTest->save();

                # Associate lab tests item to invoice
                $this->createLabTestInvoiceItem($invoice, $labTest);

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
     * Create Invoice Item (Lab Test)
     */
    public function createLabTestInvoiceItem($invoice, $labTest) {
        $test = LabTest::find($labTest['id']);

        # Calculate total price
        $totalPrice = $test['price'];
        if($labTest['discount_percentage']) {
            $discountAmt = ($test['price'] * $labTest['discount_percentage']) / 100;
            $totalPrice = $totalPrice - $discountAmt;
        }

        $invoiceItem = new InvoiceItem([
            'invoice_id' => $invoice['id'],
            'quantity' => 1,
            'unit_price' => $test['price'],
            'discount_percentage' => $labTest['discount_percentage'],
            'total_price' => $totalPrice,
        ]);
        $test->invoiceItems()->save($invoiceItem);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $visit = PatientVisit::with(['patient', 'lab_tests'])->find($id);
        $invoice = Invoice::with(['invoice_items'])->where('id',$visit->invoice_id)->first();
        $invoiceItems = [];
        if($invoice) {
            $invoiceItems = $invoice->invoice_items->map(function($item) use ($id) {
                return [
                    'invoice_id' => $item->invoice_id,
                    'description' => $item->description,
                    'unit_price' => 'PHP ' . $item->unit_price,
                    'total_price' => 'PHP ' . $item->total_price,
                    'discount_percentage' => $item->discount_percentage,
                    'discount_amount' => 'PHP ' . $item->discount_amount,
                    'lab_test' => $item->itemable,
                    'inventory_items' => PatientVisitLabTest::where('patient_visit_id', $id)
                        ->where('lab_test_id', $item->itemable->id)
                        ->first()
                        ->inventory_items
                ];
            });
        }

        return Inertia::render('PatientVisits/Show', props: [
            'visit' => $visit,
            'invoiceItems' => $invoiceItems,
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
