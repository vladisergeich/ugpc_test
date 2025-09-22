<?php

namespace App\Http\Requests\EngravingOrders;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEngravingOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:engraving_orders,id'],
            'macro_order_id' => ['sometimes', 'integer', 'exists:macro_orders,id'],
            'sequence_number' => ['sometimes', 'integer', 'min:1'],
            'order_id' => ['sometimes', 'integer', 'exists:orders,id'],
            'profile_id' => ['sometimes', 'integer', 'exists:profiles,id'],
            'status_id' => ['sometimes', 'integer', 'exists:engraving_order_statuses,id'],
            'condition_id' => ['sometimes', 'integer', 'exists:engraving_order_conditions,id'],
            'repro_id' => ['sometimes', 'integer', 'exists:vendors,id'],
            'engraver_id' => ['sometimes', 'integer', 'exists:vendors,id'],
            'designer_id' => ['sometimes', 'integer', 'exists:designers,id'],
            'gradation_increase' => ['sometimes', 'numeric'],
            'order_approval_date' => ['sometimes', 'date'],
            'cutting_line_color' => ['sometimes', 'string', 'max:255'],
            'printing_date' => ['sometimes', 'date'],
            'engraving_request_user_id' => ['sometimes', 'integer', 'exists:designers,id'],
            'engraving_request_date' => ['sometimes', 'date'],
            'quantity_shaft' => ['sometimes', 'integer'],
            'repeat_engraving_order_id' => ['sometimes', 'integer', 'exists:engraving_orders,id'],
            'comments' => ['sometimes', 'array'],
            'type_work_parameters' => ['sometimes', 'array'],
            'technological_elements' => ['sometimes', 'array'],
            'additional_options' => ['sometimes', 'array'],
            'parameters' => ['sometimes', 'array'],
            'mounting_parameter_id' => ['sometimes', 'integer', 'exists:mounting_parameters,id'],
            'format' => ['sometimes', 'numeric'],
            'material_width' => ['sometimes', 'numeric'],
            'stream_width' => ['sometimes', 'numeric'],
            'print_step' => ['sometimes', 'numeric'],
            'streams_quantity' => ['sometimes', 'integer'],
            'fragments_in_circulation' => ['sometimes', 'numeric'],
            'sleeve_length' => ['sometimes', 'numeric'],
            'engraving_width' => ['sometimes', 'numeric'],
            'previous_engraving_order_id' => ['sometimes', 'integer', 'exists:engraving_orders,id'],
            'universal_shaft' => ['sometimes', 'boolean'],
        ];
    }

    public function validatedPayload(): array
    {
        return collect($this->validated())
            ->except('id')
            ->toArray();
    }
}
