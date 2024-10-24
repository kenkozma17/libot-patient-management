<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\PatientStoreRequest;
use App\Models\Patient;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $patients = Patient::where([
            [function ($query) use ($request) {
                if (($s = $request->search)) {
                    $query->orWhere('last_name', 'LIKE', '%' . $s . '%')
                        ->orWhere('first_name', 'LIKE', '%' . $s . '%')
                        ->orWhere('phone', 'LIKE', '%' . $s . '%')
                        ->get();
                }
            }]
        ])
        ->orderBy('last_name', 'asc')
        ->paginate(config('pagination.default'))
        ->withQueryString();
        return Inertia::render('Patients/PatientsList', [
            'patients' => $patients,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Patients/Create', [
            /** Props */
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientStoreRequest $request)
    {
        $patient = new Patient;
        $patient->fill($request->validated());
        $patient->save();

        return redirect()->route('patients.show', $patient->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::find($id);
        return Inertia::render('Patients/Show', props: [
            'patient' => $patient,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $patient = Patient::Find($id);
        return Inertia::render('Patients/Edit', [
            'patient' => $patient
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientStoreRequest $request, string $id)
    {
        $patient = Patient::find($id);
        $patient->update($request->validated());
        $patient->save();

        return redirect()->route('patients.show', $patient->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Patient::destroy($id);
        return redirect()->route('patients.index');
    }
}
