<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{MailingRecipient};

class Mailing extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function recipients()
    {
        return $this->hasMany(MailingRecipient::class,'mailing_id');
    }
}
