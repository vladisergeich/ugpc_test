<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\ReEngravingApplicationApprovStage;
use App\Models\Ugpc\ReEngravingApplicationShaft;

class ReEngravingApplication extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function reEngravingStage()
    {
        return $this->hasMany(ReEngravingApplicationApprovStage::class,'app_id','id')->orderBy('stage_number');
    }

    public function activeStage()
    {
        return $this->hasOne(ReEngravingApplicationApprovStage::class,'app_id','id')->where('status','Идет согласование');
    }

    public function lastStages()
    {
        return $this->hasMany(ReEngravingApplicationApprovStage::class,'app_id','id')->where('status','Согласовано');
    }

    public function shafts()
    {
        return $this->hasMany(ReEngravingApplicationShaft::class,'app_id','id');
    }
}
