<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\ReEngravingApplicationApprovStage;
use App\User;

class ApprovHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stage()
    {
        return $this->hasOne(ReEngravingApplicationApprovStage::class,'id','stage_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
