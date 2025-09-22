<?php

namespace App\Models\Ugpc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapacityLedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'shaft_id',
        'okvid_number',
        'order_number',
        'posting_date',
        'order_number',
        'work_center',
        'machine',
        'document_number',
        'description',
        'quantity',
        'unit_of_measure_code',
        'type',
        'status',
        'operator',
        'starting_time',
        'work_shift_code',
    ];
}
