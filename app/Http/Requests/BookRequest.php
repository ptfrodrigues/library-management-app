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
        $rules = [
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'observations' => 'nullable|string',
            'cover_url' => 'nullable|url',
        ];

        if ($this->isMethod('POST')) {
            $rules['isbn'] = 'required|string|digits:13|unique:books,isbn';
        } 
        else {
            $rules = array_map(function ($rule) {
                return 'sometimes|' . $rule;
            }, $rules);

            $rules['isbn'] = 'sometimes|required|string|digits:13|unique:books,isbn,' . $this->route('book')->id;
        }

        return $rules;
    }
}
