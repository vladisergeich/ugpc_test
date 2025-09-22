<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Operation,OperationLedgerEntry,Shaft, Parameter};

class OperationLedgerEntryParameter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($param) {
            if ($shaft = $param->operationLedgerEntry->engravingOrderShaft?->shaft) {
                $attr = $param->operationLedgerEntry->operation_id == 1
                    ? [1 => 'diameter', 5 => 'thickness', 20 => 'type']
                    : [1 => 'diameter'];
                
                if (isset($attr[$param->parameter_id])) {
                    $shaft->{$attr[$param->parameter_id]} = $param->parameter_id == 20 
                        ? $param->string_value 
                        : $param->float_value;
                    $shaft->save();
                }
            }
        });
    }

    public function operationLedgerEntry()
    {
        return $this->belongsTo(OperationLedgerEntry::class, 'operation_ledger_entry_id');
    }

    public function parameter()
    {
        return $this->hasOne(Parameter::class,'id','parameter_id');
    }
}
