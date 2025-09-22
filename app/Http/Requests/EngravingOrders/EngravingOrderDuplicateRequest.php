<?php

namespace App\Http\Requests\EngravingOrders;

use Illuminate\Foundation\Http\FormRequest;

class EngravingOrderDuplicateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'macro_order_id' => ['required', 'integer', 'exists:macro_orders,id'],
        ];
    }
}
