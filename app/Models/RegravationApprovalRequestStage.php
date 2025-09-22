<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{RegravationStage};

class RegravationApprovalRequestStage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function stage()
    {
        return $this->hasOne(RegravationStage::class,'id','regravation_stage_id');
    }
}
