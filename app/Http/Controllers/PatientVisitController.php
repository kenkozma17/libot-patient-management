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
use App\Models\PatientVisitLabTestResult;
use App\Notifications\LowInventory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;


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

        $totalAmountPayable = 0;

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
                }

                # Get total amount payable from lab test prices
                $totalAmountPayable += LabTest::find($labTest['id'])->price;
            }
        }

        # Save Invoice with total amount to pay
        $invoice->update([
            'amount_payable' => $totalAmountPayable,
            'or_number' => $request->or_number,
            'discount_percentage' => $request->discount_percentage,
            'amount_discounted' => ($totalAmountPayable * $request->discount_percentage) / 100,
            'is_paid' => $request->is_paid ? 1 : 0,
        ]);
        $invoice->save();

        return redirect()->route('patients.show', $patientVisit->patient_id);
    }

    /**
     * Create Invoice Item (Lab Test)
     */
    public function createLabTestInvoiceItem($invoice, $labTest) {
        $test = LabTest::find($labTest['id']);

        # Calculate total price
        $totalPrice = $test['price'];

        $invoiceItem = new InvoiceItem([
            'invoice_id' => $invoice['id'],
            'quantity' => 1,
            'unit_price' => $test['price'],
            'total_price' => $totalPrice,
        ]);
        $test->invoiceItems()->save($invoiceItem);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $visit = PatientVisit::with(['patient', 'lab_tests', 'results'])->find($id);
        $invoice = Invoice::with(['invoice_items'])->where('id',$visit->invoice_id)->first();
        $invoiceItems = [];
        if($invoice) {
            $invoiceItems = $invoice->invoice_items->map(function($item, $key) use ($id, $visit) {
                return [
                    'invoice_id' => $item->invoice_id,
                    'description' => $item->description,
                    'unit_price' => 'PHP ' . $item->unit_price,
                    'total_price' => 'PHP ' . $item->total_price,
                    'discount_percentage' => $item->discount_percentage,
                    'discount_amount' => 'PHP ' . $item->discount_amount,
                    'lab_test' => $item->itemable,
                    'patient_visit_lab_test_id' => $visit->lab_tests[$key]->pivot->id,
                    'is_consumed' => $visit->lab_tests[$key]->pivot->is_consumed ? true : false,
                    'invoice_item_id' => $item->id,
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
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $visit = PatientVisit::with('patient')->find($id);
        return Inertia::render('PatientVisits/Edit', props: [
            'visit' => $visit,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientVisitStoreRequest $request, string $id)
    {
        $item = PatientVisit::find($id);
        $item->update($request->validated());
        $item->save();

        return redirect()->route('patient-visits.show', $id);
    }

    /**
     * Upload Lab Test Result Files
     */
    public function uploadResults(Request $request, $patientVisitId) {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10000',
        ]);

        $files = $request->file('files');
        if($files) {
            foreach($files as $file) {
                $fileName = uniqid().time().'.'.$file->getClientOriginalExtension();
                $path = $file->move(public_path('uploads'), $fileName);

                if($path) {
                    $newFile = new PatientVisitLabTestResult([
                        'patient_visit_id' => $patientVisitId,
                        'result_name' => $file->getClientOriginalName(),
                        'result_path' => 'uploads/' . $fileName,
                    ]);
                    $newFile->save();
                }
            }
        }
    }

    /**
     * Update consumption status of lab test
     */
    public function updateConsumption(Request $request, $patientVisitLabTestId) {
        $isConsumed = $request->is_consumed;
        $visitLabTest = PatientVisitLabTest::find($patientVisitLabTestId);
        $visitLabTest->is_consumed = $isConsumed ? 1 : 0;
        $visitLabTest->save();

        # Fetch inventory item that will be used for transaction
        $pLabTestInventoryItem = PatientVisitLabTestInventoryItem::
            where('visit_lab_test_id', $patientVisitLabTestId)
            ->first();

        # Create new transaciton for inventory item that was consumed
        $transaction = new InventoryTransaction;
        $transaction->inventory_item_id = $pLabTestInventoryItem->inventory_item_id;
        $transaction->quantity = $pLabTestInventoryItem->quantity;
        $transaction->patient_visit_id = $request->patient_visit_id;
        $transaction->transaction_type = 'DECREASE';

        $inventoryItem = InventoryItem::find($pLabTestInventoryItem->inventory_item_id);
        $currentStock = $inventoryItem->current_stock;
        $newStock = $currentStock - $pLabTestInventoryItem->quantity;
        $transaction->stock = $newStock;
        $transaction->save();

        // Create notification if low stock level is met
        if($newStock <= $inventoryItem->low_stock_limit) {
            $inventoryItem->notify(new LowInventory($inventoryItem));
        }
    }

    /**
     * Delete Lab Test within Patient Transaction
     */
    public function destroyLabTest(Request $request, $patientVisitLabTestId) {
        $invoiceId = $request->invoice_id;
        $invoiceItemId = $request->invoice_item_id;

        if($invoiceItemId) {
            InvoiceItem::destroy($invoiceItemId);
            PatientVisitLabTest::destroy($patientVisitLabTestId);
        }

        # Update amounts
        if($invoiceId) {
            $invoice = Invoice::find($invoiceId);

            # Adjust invoice totals
            if($invoice->invoice_items()->exists()) {
                $invoiceItems = $invoice->invoice_items()->get();
                $amountPayable = 0;
                foreach($invoiceItems as $item) {
                    $amountPayable += $item->unit_price;
                }
                $discountAmount = ($amountPayable * $invoice->discount_percentage) / 100;
                $amountDue = $amountPayable - $discountAmount;
                $invoice->update([
                    'amount_payable' => $amountDue,
                    'amount_discounted' => $discountAmount
                ]);
            } else {
                # Set invoice to nothing if no more items exist.
                $invoice->update([
                    'amount_payable' => 0,
                    'discount_percentage' => 0,
                    'amount_didscounted' => 0
                ]);
            }
            $invoice->save();
        }
    }

    /**
     * Delete Lab Result File
     */
    public function destroyFile($fileId) {
        PatientVisitLabTestResult::destroy($fileId);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
