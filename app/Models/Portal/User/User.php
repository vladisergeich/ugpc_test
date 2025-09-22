<?php

namespace App\Models\Portal\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use App\Models\Portal\Role\Role;

class User extends Authenticatable
{
    use  HasApiTokens, HasFactory;

    protected $primaryKey = 'id';
    

    protected $connection = 'mainportal2';

    use Notifiable;


}
