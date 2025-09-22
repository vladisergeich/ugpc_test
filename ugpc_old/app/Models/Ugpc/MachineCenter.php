<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\Stage;
use App\Models\Ugpc\WorkCenter;
use App\Models\Ugpc\GlobalData;

class MachineCenter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function stages()
    {
        return $this->hasMany(Stage::class,'work_center','work_center');
    }

    public function workCenter()
    {
        return $this->hasOne(WorkCenter::class,'work_center','work_center');
    }

    public function cascade()
    {
        return $this->hasOne(GlobalData::class,'variable_desc','id')->where('variable_name','cascade');
    }

    public function endCutter()
    {
        return $this->hasOne(GlobalData::class,'variable_desc','id')->where('variable_name','end_cutter');
    }

    public function mainCutter()
    {
        return $this->hasOne(GlobalData::class,'variable_desc','id')->where('variable_name','main_cutter');
    }

}
