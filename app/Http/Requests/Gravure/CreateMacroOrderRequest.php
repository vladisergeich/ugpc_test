<?php

namespace App\Http\Requests\Gravure;

use Illuminate\Foundation\Http\FormRequest;

class CreateMacroOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ];
    }
}
