<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Model;

class InputControl extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'shaft_id',
        'okvid_number',
        'document_date',
        'order_number',
        'type_shaft',
        'edge_radius',
        'copper_plating',
        'roughness',
        'defect',
        'defect_reason',
        'work_shift_code',
        'shaft_diameter',
        'post'
    ];
}
