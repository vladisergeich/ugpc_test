<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Portal\Role\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Danaflex\KeycloakWebGuard\Traits\KeycloakModelTrait as KeycloakModel;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mainportal2';

    use Notifiable, KeycloakModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany(Role::class, "$database.role_user", 'user_id', 'role_id');
    }

    public function checkRole($role)
    {
        return $this->roles->contains('name', $role);
    }
}
