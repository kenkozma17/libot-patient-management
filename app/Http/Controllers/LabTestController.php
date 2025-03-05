<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryStoreRequest;
use App\Http\Requests\LabTestStoreRequest;
use App\Models\InventoryItemCategory;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class LabTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $labTests = LabTest::where([
            [function ($query) use ($request) {
                if (($s = $request->search)) {
                    $query->orWhere('name', 'LIKE', '%' . $s . '%')
                        ->get();
                }
            }]
        ])
        ->with('category')
        ->orderBy('name', 'asc');

        # Filtering
        $columnFilters = $request->column_filters ? json_decode($request->column_filters, true) : [];
        if($columnFilters) {
            foreach ($columnFilters as $column) {
                if (!empty($column['value'])) {
                    if($column['field'] === 'category') {
                        $labTests
                            ->whereRelation('category', 'name', 'like', "%{$column['value']}%");
                    } else {
                        $labTests
                            ->where($column['field'], 'like', "%{$column['value']}%");
                    }
                }
            }
        }
        $labTests = $labTests
            ->paginate(config('pagination.default'))
            ->withQueryString();

        return Inertia::render('LabTests/LabTestsList', [
            'lab_tests' => $labTests,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = InventoryItemCategory::orderByDesc('name')->get();
        return Inertia::render('LabTests/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabTestStoreRequest $request)
    {
        $item = new LabTest;
        $item->fill($request->validated());
        $item->slug = Str::slug($item->name);
        $item->save();

        return redirect()->route('lab-tests.index');
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
        $test = LabTest::Find($id);
        $categories = InventoryItemCategory::orderByDesc('name')->get();
        return Inertia::render('LabTests/Edit', [
            'test' => $test,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabTestStoreRequest $request, string $id)
    {

        $item = LabTest::find($id);
        $item->update($request->validated());
        $item->slug = Str::slug($item->name);
        $item->save();

        return redirect()->route('lab-tests.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        LabTest::destroy($id);
        return redirect()->route('lab-tests.index');
    }
}
