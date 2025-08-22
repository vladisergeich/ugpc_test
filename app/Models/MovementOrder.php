<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\EngravingOrder;
use App\Models\ManufactureRequest;

class MovementOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'completion_date',
        'note',
        'priority_number',
        'status',
        'engraver_id',
        'related_type',
        'related_id',
        'type',
    ];

    protected $appends = ['shafts_in_warehouse'];

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_AWAIT = 'await';
    const STATUS_REMOVED = 'removed';

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function engravingOrder(): ?EngravingOrder
    {
        return $this->related_type === EngravingOrder::class ? $this->related : null;
    }

    public function manufactureRequest(): ?ManufactureRequest
    {
        return $this->related_type === ManufactureRequest::class ? $this->related : null;
    }

    public function isOrder(): bool
    {
        return $this->type === 'order';
    }

    public function isDowntime(): bool
    {
        return $this->type === 'downtime';
    }

    public function isRequest(): bool
    {
        return $this->type === 'request';
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_AWAIT => 'Ожидает',
            self::STATUS_REMOVED => 'Снят',
        ];
    }

    public function getShaftsInWarehouseAttribute(): bool
    {
        $engravingOrder = $this->engravingOrder();

        if (!$engravingOrder || !$engravingOrder->engravingOrderShaft) {
            return false;
        }

        $engraverId = $engravingOrder->engraver_id;

        foreach ($engravingOrder->engravingOrderShaft as $shaftLink) {
            if ($shaftLink->shaft?->warehouse_id !== $engraverId) {
                return false;
            }
        }

        return true;
    }

    public function getStatus(): string
    {
        return self::statuses()[$this->status] ?? 'Неизвестный статус';
    }
}
