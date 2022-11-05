<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{
    const EASY = 'Easy';
    const MEDIUM = 'Medium';
    const HARD = 'Hard';
    const VERY_HARD = 'Very Hard';

    protected $fillable = ['title'];
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
