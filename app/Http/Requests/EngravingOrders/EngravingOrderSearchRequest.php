<?php

namespace App\Http\Requests\EngravingOrders;

use Illuminate\Foundation\Http\FormRequest;

class EngravingOrderSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'mode' => ['nullable', 'in:batch,product,shaft'],
        ];
    }
}
