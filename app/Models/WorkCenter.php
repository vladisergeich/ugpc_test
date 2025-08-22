<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Operation};

class WorkCenter extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function secondaryOperations()
    {
        return $this->hasMany(Operation::class,'work_center_id','id')->where('type','secondary');
    }
}
