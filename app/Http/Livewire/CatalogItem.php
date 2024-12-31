<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Catalog;

class CatalogItem extends Component
{
    public Catalog $catalog;

    public function toggleFeatured()
    {
        $this->catalog->is_featured = !$this->catalog->is_featured;
        $this->catalog->save();
    }

    public function render()
    {
        return view('livewire.catalog-item');
    }
}

