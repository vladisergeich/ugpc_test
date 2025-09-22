<?php

namespace App\Http\Requests\EngravingOrderShafts;

use Illuminate\Foundation\Http\FormRequest;

class ReturnEngravingOrderShaftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:engraving_order_shafts,id'],
            'engraving_order_id' => ['required', 'integer', 'exists:engraving_orders,id'],
            'shaft_id' => ['required', 'integer', 'exists:shafts,id'],
        ];
    }
}
