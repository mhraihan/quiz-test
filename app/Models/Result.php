<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;
    protected $casts = [
        'complete' => 'boolean',
        'score' => 'integer',
        'correct_answered' => 'integer',
        'questions_answered' => 'array',
        'is_active' => 'boolean',
        'start_time' => 'integer',
        'stop_time' => 'integer',
        'total_questions' => 'integer',
    ];

    protected $fillable = [
        'class_id',
        'complete',
        'correct_answered',
        'score',
        'start_time',
        'stop_time',
        'questions_answered',
        'total_questions'
    ];
    /**
     *Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
