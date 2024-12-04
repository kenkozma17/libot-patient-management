<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientVisitStoreRequest;
use App\Models\Patient;
use App\Models\PatientVisit;
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
        return Inertia::render('PatientVisits/Create', props: [
            'patient' => $patient,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientVisitStoreRequest $request)
    {
        $patientVist = new PatientVisit;
        $patientVist->fill($request->validated());
        $patientVist->save();

        return redirect()->route('patients.show', $patientVist->patient_id);
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
