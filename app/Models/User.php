<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'photo_path',
        'gender',
        'birthday',
        'country',
        'state',
        'city',
        'phone',
        'address',
        'postcode',
        'active',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'datetime',
        'active' => 'boolean',
    ];

    public function name()
    {
        return $this->first_name.' ' . $this->last_name;
    }
    public function isAdmin(): Bool
    {
        $role = $this->roles()->pluck('name')->first();
        return $role === 'super-admin' || $role === 'admin';
    }
    public function getRedirectRoute()
    {
        return match($this->roles()->pluck('name')->first()) {
            'super-admin', 'admin' => 'dashboard',
            'teacher' => 'results.index',
            'student' => 'results.index',
        };
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
