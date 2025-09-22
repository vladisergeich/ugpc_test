<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\ApprovStage;
use App\Models\Ugpc\ApprovHistory;

class ReEngravingApplicationApprovStage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function approvStage()
    {
        return $this->hasOne(ApprovStage::class,'id','stage_id');
    }

    public function stageHistory()
    {
        return $this->hasMany(ApprovHistory::class,'stage_id','id');
    }

    public function lastHistory()
    {
        return $this->hasOne(ApprovHistory::class,'stage_id','id')->orderBy('id','desc');
    }
}
