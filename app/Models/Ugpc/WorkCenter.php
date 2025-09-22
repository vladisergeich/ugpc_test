<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\Operation;
use App\Models\Ugpc\MachineCenter;

class WorkCenter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function mainOperation()
    {
        return $this->hasMany(Operation::class,'work_center','work_center')->where('type','main');
    }

    public function machines()
    {
        return $this->hasMany(MachineCenter::class,'work_center','work_center')->groupBy('machine')->distinct();
    }
}
