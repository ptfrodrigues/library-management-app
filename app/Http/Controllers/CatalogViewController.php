<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;
use Illuminate\Http\Request;
use App\Models\Catalog;

class CatalogViewController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Catalog::class);

        $items = $this->catalogService->getFilteredCatalog($request->all());
        $fields = Catalog::getAllFields();
        $tableFields = Catalog::getVisibleFields();

        return view('pages.dashboard.catalogs', compact('items', 'fields', 'tableFields'));
    }

    public function home(Request $request)
    {
        $items = $this->catalogService->getFilteredCatalog($request->all());

        return view('pages.home', compact('items'));
    }
}