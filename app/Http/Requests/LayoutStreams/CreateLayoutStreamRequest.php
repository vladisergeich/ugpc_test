<?php

namespace App\Http\Requests\LayoutStreams;

use Illuminate\Foundation\Http\FormRequest;

class CreateLayoutStreamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'engraving_order_id' => ['required', 'integer', 'exists:engraving_orders,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
