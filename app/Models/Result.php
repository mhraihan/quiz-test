<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $appends = ['exam', 'score'];

    protected $casts = [
        'complete' => 'boolean',
        'correct_answered' => 'integer',
        'questions_answered' => 'array',
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'stop_time' => 'datetime',
        'total_questions' => 'integer',
    ];

    protected $fillable = [
        'class_id',
        'complete',
        'correct_answered',
        'start_time',
        'stop_time',
        'questions_answered',
        'total_questions',
        'language',
    ];

    public function resolveRouteBinding($value, $field = null): Model|Builder|null
    {
        return $this->with('user')->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
    }

    public function exam(): Attribute
    {
        $options = [
            'join' => true,
            'parts' => 3,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        return Attribute::make(
            get: fn() => [
                'duration' => $this->stop_time->diffForHumans($this->start_time, $options),
                'how_long' => $this->created_at->diffForHumans(),
                'day' => $this->created_at->format('l'),
                'date' => $this->created_at->format('g:i A d/m/Y')
            ]
        );
    }

    public function score(): Attribute
    {
        return Attribute::make(
            get: fn() => (float)number_format(($this->correct_answered / $this->total_questions) * 100, 2)
        );
    }


    /**
     *Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
