<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'role' => ['required', Rule::in(['admin', 'manager', 'librarian', 'member'])],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|string|min:8';
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }
}

