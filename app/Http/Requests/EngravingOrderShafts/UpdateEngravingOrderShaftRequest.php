<?php

namespace App\Http\Requests\EngravingOrderShafts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEngravingOrderShaftRequest extends FormRequest
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
            'sequence_number' => ['sometimes', 'integer', 'min:1'],
            'shaft_id' => ['nullable', 'integer', 'exists:shafts,id'],
            'status_id' => ['nullable', 'integer'],
            'status' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:255'],
            'lineature' => ['nullable', 'string', 'max:255'],
            'corner' => ['nullable', 'string', 'max:255'],
            'cutter' => ['nullable', 'string', 'max:255'],
            'engraving_time' => ['nullable', 'date'],
            'diameter' => ['nullable', 'numeric'],
            'note' => ['nullable', 'string'],
            'parameters' => ['sometimes', 'array'],
            'parameters.reengraving' => ['sometimes', 'boolean'],
            'parameters.shortening_scale_length' => ['sometimes', 'boolean'],
            'final_diameter' => ['sometimes', 'numeric'],
            'write_off_date' => ['sometimes', 'date'],
        ];
    }

    public function payload(): array
    {
        return collect($this->validated())
            ->except(['id', 'engraving_order_id'])
            ->toArray();
    }
}
