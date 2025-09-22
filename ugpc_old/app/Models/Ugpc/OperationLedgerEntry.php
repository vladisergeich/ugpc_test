<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\Operation;
use App\Models\Ugpc\ParameterLedgerEntry;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\EngravingStage;
use App\Models\Ugpc\MachineCenter;
use App\Models\Ugpc\WorkShiftCode;
use App\User;

class OperationLedgerEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [

    ];

    public function operation()
    {
        return $this->hasOne(Operation::class,'id','operation_id');
    }

    public function machine()
    {
        return $this->hasOne(MachineCenter::class,'id','machine_id');
    }

    public function engravingOrder()
    {
        return $this->hasOne(EngravingOrder::class,'id','engraving_order_id');
    }

    public function factDiameter()
    {
        return $this->hasOne(ParameterLedgerEntry::class,'operation_ledger_entry_id','id')->where('parameter_id',1);
    }

    public function factHardness()
    {
        return $this->hasOne(ParameterLedgerEntry::class,'operation_ledger_entry_id','id')->where('parameter_id',4);
    }

    public function diameterDifference()
    {
        return $this->hasOne(ParameterLedgerEntry::class,'operation_ledger_entry_id','id')->where('parameter_id',6);
    }

    public function thickness()
    {
        return $this->hasOne(ParameterLedgerEntry::class,'operation_ledger_entry_id','id')->where('parameter_id',5);
    }

    public function cutter()
    {
        return $this->hasOne(ParameterLedgerEntry::class,'operation_ledger_entry_id','id')->where('parameter_id',13);
    }

    public function headNumber()
    {
        return $this->hasOne(ParameterLedgerEntry::class,'operation_ledger_entry_id','id')->where('parameter_id',12);
    }

    public function bath()
    {
        return $this->hasOne(ParameterLedgerEntry::class,'operation_ledger_entry_id','id')->where('parameter_id',16);
    }


    public function stage()
    {
        return $this->hasOne(EngravingStage::class,'id','engraving_stage_id');
    }

    public function workShift()
    {
        return $this->hasOne(WorkShiftCode::class,'code','work_shift_code');
    }

    public function factParameters()
    {
        return $this->hasMany(ParameterLedgerEntry::class,'operation_ledger_entry_id','id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
