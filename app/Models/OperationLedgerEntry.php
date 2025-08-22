<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Operation,PhaseStage, OperationLedgerEntryParameter, WorkShiftCode, MachineCenter};
use App\Events\StageUpdated;

class OperationLedgerEntry extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $preCopper = false;

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    public static function statuses(): array
    {
        return [
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Завершен',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($operation) {
            
        });

        static::created(function ($operation) {
            if ($operation->phaseStage) {
                broadcast(new StageUpdated($operation->phaseStage->load('operation')));
            }
        });
    }

    public function phaseStage()
    {
        return $this->belongsTo(PhaseStage::class, 'phase_stage_id');
    }

    public function getStatusShaft(): string
    {
        return self::statuses()[$this->status] ?? 'Неизвестный статус';
    }

    public function machine()
    {
        return $this->hasOne(MachineCenter::class,'id','machine_id');
    }

    public function operation()
    {
        return $this->hasOne(Operation::class,'id','operation_id');
    }

    public function workShiftCode()
    {
        return $this->hasOne(WorkShiftCode::class,'id','work_shift_code_id');
    }

    public function engravingOrderShaft()
    {
        return $this->hasOne(EngravingOrderShaft::class,'id','engraving_order_shaft_id');
    }

    public function parameters()
    {
        return $this->hasMany(OperationLedgerEntryParameter::class,'operation_ledger_entry_id','id');
    }

}
