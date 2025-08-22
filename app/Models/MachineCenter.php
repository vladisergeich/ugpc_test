<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{OperationLedgerEntry,WorkCenter,PhaseStage, Stage, ShaftConsumption};

class MachineCenter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function shaftsInWarehouse()
    {
        return $this->hasManyThrough(
            PhaseStage::class, // Конечная таблица
            Stage::class, // Промежуточная таблица
            'work_center_id', // Ключ в Stage, связывающий с WorkCenter
            'stage_id', // Ключ в PhaseStage, связывающий со Stage
            'work_center_id', // Ключ в Machine, связывающий с WorkCenter
            'id' // Первичный ключ WorkCenter
        )
        ->where('status', 'in_progress')
        ->whereDoesntHave('phase.engravingOrderShaft.consumpShaft', function ($query) {
            $query->where('engraving_order_shaft_id', '!=', null);
        })
        ->with(['phase.engravingOrderShaft.engravingOrder.movementOrder' => function ($query) {
            $query->orderBy('completion_date', 'asc');
        }]);
    }

    public function operationsInWorkShift()
    {
        $workShift = WorkShiftCode::where('status','processing')->first();

        return $this->hasMany(OperationLedgerEntry::class,'machine_id')->where('work_shift_code_id',$workShift->id ?? null);
    }

    public function completeOperations()
    {
        return $this->hasMany(OperationLedgerEntry::class,'machine_id')->where('status','completed');
    }

    public function currentShaft()
    {
        return $this->hasOne(OperationLedgerEntry::class,'machine_id')->where('status','in_progress')->whereNotNull('engraving_order_shaft_id');
    }

    public function currentOperation()
    {
        return $this->hasOne(OperationLedgerEntry::class,'machine_id')->where('status','in_progress');
    }

    public function workCenter()
    {
        return $this->hasOne(WorkCenter::class,'id','work_center_id');
    }

    public function consumpShaft()
    {
        return $this->hasOne(ShaftConsumption::class,'machine_center_id','id');
    }
}
