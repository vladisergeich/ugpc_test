<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Operation};

class StageOperation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function operation()
    {
        return $this->hasOne(Operation::class,'id','operation_id');
    }
}
