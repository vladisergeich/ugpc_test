<?php

namespace App\Http\Requests\Gravure;

use Illuminate\Foundation\Http\FormRequest;

class MacroLookupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'macro' => ['required', 'integer', 'exists:macro_orders,id'],
        ];
    }
}
