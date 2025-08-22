<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{PhaseStage, EngravingOrderShaft};
use Illuminate\Database\Eloquent\Casts\Attribute;

class Phase extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'sequence', 'id', 'color','engraving_order_shaft_id','status'
    ];

    protected $appends = ['stages_by_stage_id','status_text'];
    

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    public static function statuses(): array
    {
        return [
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_REJECTED => 'Брак',
            self::STATUS_COMPLETED => 'Завершен',
        ];
    }

    public function getStatusTextAttribute()
    {
        return $this->getStatusShaft();
    }

    protected static function booted(): void
    {
        static::updated(function (self $phase) {
            match ($phase->status) {
                self::STATUS_COMPLETED => $phase->handleCompletedStatus(),
                default => null,
            };
        });
    }

    protected function handleCompletedStatus(): void
    {
        $nextPhase = self::query()
            ->where('engraving_order_shaft_id', $this->engraving_order_shaft_id)
            ->where('sequence', '>', $this->sequence)
            ->orderBy('sequence')
            ->first();

        $nextPhase
            ? $nextPhase->handleProgressStatus()
            : $this->engravingOrderShaft->update(['status' => 'completed']);
    }

    protected function handleProgressStatus(): void
    {
        $this->update(['status' => 'in_progress']);
        $this->stages()
        ->where('sequence', 1)
        ->update(['status' => self::STATUS_IN_PROGRESS]);
    }

    public function getStatusShaft(): string
    {
        return self::statuses()[$this->status] ?? 'Неизвестный статус';
    }

    public function engravingOrderShaft()
    {
        return $this->hasOne(EngravingOrderShaft::class,'id','engraving_order_shaft_id');
    }

    public function stages()
    {
        return $this->hasMany(PhaseStage::class)->orderBy('sequence');
    }

    public function getStagesByStageIdAttribute()
    {
        return $this->stages->keyBy('stage_id')->toArray();  // Преобразуем коллекцию в массив
    }

    /*
    protected static function boot()
    {
        parent::boot();

        static::created(function ($phase) {

        });
    }
        */
    
}
