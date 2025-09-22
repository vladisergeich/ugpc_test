<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use App\Models\Portal\Role\Role;

class AccessToken extends Model
{
    use  HasApiTokens, HasFactory;

    protected $connection = 'mysql';

    use Notifiable;

}
