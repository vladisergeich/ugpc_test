<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\Operation;

class Stage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function operations()
    {
        return $this->belongsToMany(Operation::class,'operation_stages','stage_id','operation_id');
    }
}
