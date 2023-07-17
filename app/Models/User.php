<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
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
        'recent_exam'

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
        'birthday' => 'date:Y-m-d',
        'active' => 'boolean',
    ];

    public function name(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isSuperAdmin(): bool
    {
        return app('userRole') === UserEnum::SUPER_ADMIN->value;
    }

    public function isAdmin(): bool
    {
        $role = app('userRole');
        return $role === UserEnum::SUPER_ADMIN->value || $role === UserEnum::ADMIN->value;
    }

    public function isStudent(): bool
    {
        return app('userRole') === UserEnum::STUDENT->value;
    }

    public function isTeacher(): bool
    {
        return app('userRole') === UserEnum::TEACHER->value;
    }

    public function getRedirectRoute(): string
    {
        return match ($this->roles()->pluck('name')->first()) {
            UserEnum::SUPER_ADMIN->value, UserEnum::ADMIN->value => 'dashboard',
            UserEnum::TEACHER->value => 'teacher.index',
            UserEnum::STUDENT->value => 'results.index',
        };
    }

    /**
     * Filter
     */
    public function scopeOrderByName($query): void
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['column'] ?? null, function ($query, $column) {
            $query->orderBy($column, request('direction'));
        }, function ($query) {
            $query->latest();
        });
    }

    /**
     *Relations
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }


    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'teacher_user', 'user_id', 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'teacher_user', 'teacher_id', 'user_id');
    }

    /**
     * Mutators
     */
    public function isTeacherStudent(): bool
    {
        $cacheKey = 'isTeacherStudent:' . $this->id;

        return cache()->remember($cacheKey,  config('quiz.cache'), function () {
            return $this->teachers()->exists();
        });
    }

    public function belongsToTeacher($teacherId): bool
    {
        $cacheKey = 'belongsToTeacher:' . $this->id . ':' . $teacherId;

        return cache()->remember($cacheKey,  config('quiz.cache'), function () use ($teacherId) {
            return $this->teachers()->where('teacher_id', $teacherId)->exists();
        });
    }

}
