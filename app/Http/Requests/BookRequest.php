<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'isbn' => [
                'required',
                'string',
                Rule::unique('books')->ignore($this->book),
            ],
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'observations' => 'nullable|string',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
        ];
    }
}

