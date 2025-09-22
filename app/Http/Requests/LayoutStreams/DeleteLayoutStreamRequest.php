<?php

namespace App\Http\Requests\LayoutStreams;

use Illuminate\Foundation\Http\FormRequest;

class DeleteLayoutStreamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:layout_constructors,id'],
            'engraving_order_id' => ['required', 'integer', 'exists:engraving_orders,id'],
        ];
    }
}
