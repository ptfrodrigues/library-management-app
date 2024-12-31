<?php

namespace App\Http\Requests;

use App\Models\Catalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogRequest extends FormRequest
{
    public function authorize()
    {
        $user = $this->user();
        $catalog = $this->route('catalog');

        return match ($this->method()) {
            'POST' => $user->can('create', Catalog::class),
            'PUT', 'PATCH' => $user && $catalog && $user->can('update', $catalog),
            'DELETE' => $user && $catalog && $user->can('delete', $catalog),
            default => false,
        };
    }

    public function rules()
    {
        $rules = [
            'book_id' => ['required', 'exists:books,id', Rule::unique('catalogs')->ignore($this->catalog)],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['book_id'] = ['sometimes', 'required', 'exists:books,id', Rule::unique('catalogs')->ignore($this->catalog)];
        }

        return $rules;
    }
}