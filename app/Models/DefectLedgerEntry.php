<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DefectLedgerEntry extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'engraving_order_shaft_id', 'phase_stage_id', 'user_id', 'work_shift_code_id', 'machine_id', 'reason_id', 'note',
    ];
}
