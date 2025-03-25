<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientLoanStoreRequest;
use App\Models\LoanPayment;
use App\Models\Patient;
use App\Models\PatientLoan;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Inertia\Inertia;

class PatientLoansController extends Controller
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
        return Inertia::render('PatientLoans/Create', props: [
            'patient' => $patient,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientLoanStoreRequest $request)
    {
        # Create new patient loan
        $patientLoan = new PatientLoan;
        $patientLoan->fill($request->validated());
        $patientLoan->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patientLoan = PatientLoan::where('id', $id)
            ->with('patient')->first();

        $loanPayments = LoanPayment::where('patient_loan_id', $id)
            ->orderBy('payment_date', 'desc')
            ->paginate(config('pagination.default'))
            ->withQueryString();

        return Inertia::render('PatientLoans/Show', [
            'loan' => $patientLoan,
            'payments' => $loanPayments
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
