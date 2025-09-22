<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ugpc\RouteMapStage;

class RouteMapCard extends Model
{
    
    use HasFactory;

    protected $guarded = ['id'];

    public function stages()
    {
        return $this->hasMany(RouteMapStage::class,'shaft_id', 'shaft_id');
    }
}
