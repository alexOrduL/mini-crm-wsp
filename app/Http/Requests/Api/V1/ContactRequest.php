<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {

        $rules = [
            'company_id' => 'nullable|exists:companies,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts',
            'phone' => 'nullable|string|max:20',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'] = 'required|email|unique:contacts,email,'.$this->contact->id;
        }

        return $rules;
    }
}