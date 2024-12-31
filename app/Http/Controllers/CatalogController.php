<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogRequest;
use App\Models\Catalog;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
        $this->authorizeResource(Catalog::class, 'catalog', [
            'except' => ['index', 'create']
        ]);
    }

    public function create(){}

    public function store(CatalogRequest $request)
    {
        $this->catalogService->create($request->validated());

        return redirect()->route('dashboard.books')
            ->with('success', 'Catalog entry created successfully.');
    }

    public function edit(Catalog $catalog)
    {
        return view('catalogs.edit', compact('catalog'));
    }

    public function update(CatalogRequest $request, Catalog $catalog)
    {
        $this->catalogService->update($catalog, $request->validated());

        return redirect()->route('dashboard.catalogs')
            ->with('success', 'Catalog entry updated successfully.');
    }

    public function destroy(Catalog $catalog)
    {
        $this->catalogService->forceDelete($catalog);

        return redirect()->route('dashboard.catalogs')
            ->with('success', 'Catalog entry deleted successfully.');
    }
}