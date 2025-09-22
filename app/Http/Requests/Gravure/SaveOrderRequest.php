<?php

namespace App\Http\Requests\Gravure;

use App\Domain\Orders\DataTransferObjects\SaveOrderData;
use Illuminate\Foundation\Http\FormRequest;

class SaveOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => ['required', 'string'],
            'engraving_order_id' => ['required', 'integer', 'exists:engraving_orders,id'],
        ];
    }

    public function toData(): SaveOrderData
    {
        $validated = $this->validated();

        return new SaveOrderData(
            $validated['order_number'],
            (int) $validated['engraving_order_id']
        );
    }
}
