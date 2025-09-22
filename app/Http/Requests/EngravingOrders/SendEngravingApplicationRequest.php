<?php

namespace App\Http\Requests\EngravingOrders;

use Illuminate\Foundation\Http\FormRequest;

class SendEngravingApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'engraving_order_id' => ['required', 'integer', 'exists:engraving_orders,id'],
        ];
    }
}
