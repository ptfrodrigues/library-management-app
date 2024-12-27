<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $this->route('book')->id,
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'observations' => 'nullable|string',
        ];
    }
}

