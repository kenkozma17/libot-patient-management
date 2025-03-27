<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanPaymentStoreRequest;
use App\Models\LoanPayment;
use App\Models\PatientLoan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanPaymentController extends Controller
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
    public function create($loanId)
    {
        $loan = PatientLoan::with(['patient', 'payments'])->find($loanId);

        return Inertia::render('LoanPayments/Create', [
            'loan' => $loan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LoanPaymentStoreRequest $request)
    {
        # Create new patient loan payment
        $payment = new LoanPayment;
        $payment->fill($request->validated());

        # Calculate remaining balance
        $patientLoanId = $payment->patient_loan_id;
        $totalPaymentsAmount = LoanPayment::
            where('patient_loan_id', $patientLoanId)
            ->sum('amount');
        $loan = PatientLoan::find($patientLoanId);
        $payment->remaining_balance = $loan->amount - ($payment->amount + $totalPaymentsAmount);

        // Change status if fully paid.
        if($payment->remaining_balance == 0) {
            $loan->status = 'fully paid';
            $loan->save();
        }

        $payment->save();
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
