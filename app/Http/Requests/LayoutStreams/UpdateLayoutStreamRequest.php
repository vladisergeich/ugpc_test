<?php

namespace App\Http\Requests\LayoutStreams;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLayoutStreamRequest extends FormRequest
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
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'stream_number' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function payload(): array
    {
        return collect($this->validated())
            ->except(['id', 'engraving_order_id'])
            ->toArray();
    }
}
