<?php

namespace App\Http\ViewComposers;

use App\Services\CatalogService;
use Illuminate\View\View;

class CatalogFilterComposer
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function compose(View $view)
    {
        $view->with('filters', $this->catalogService->getFilterOptions());
    }
}