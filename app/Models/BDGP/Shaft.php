<?php

namespace App\Models\BDGP;
use App\Models\BDGP\ShaftOrder;

use Illuminate\Database\Eloquent\Model;

class Shaft extends Model
{
    protected $casts = [
        'manufacture_date' => 'datetime:d.m.Y',
    ];
    
    public function okvid()
    {
        return $this->hasOne(ShaftOrder::class,'shaft_id','shaft_id')->orderByRaw('CASE WHEN writeoff_date IS NULL THEN 0 ELSE 1 END, writeoff_date DESC');
    }

    public function lastOkvid()
    {
        return $this->hasOne(ShaftOrder::class,'shaft_id','shaft_id')->orderBy('writeoff_date','desc')->where('written_off',true);
    }
}
