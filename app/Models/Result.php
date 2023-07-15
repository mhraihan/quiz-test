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
        'total_questions'
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

    public function getDataFromQuestions($query)
    {
        $ids = array_column($query, 'id');
        $answers = array_column($query, 'answer');
        $questions = Question::query()->whereIn('id', $ids)->select('title', 'details', 'correct_answer', 'explain', 'options')->get();
        $answered = collect($questions)->pluck('correct_answer')->map(function ($item, $key) use ($answers, $questions) {
            $answer = $item === $answers[$key];
            $questions[$key]['answer'] = $answers[$key];
            return $answer;
        });
        $correct_answered = count($answered->filter());

        return [
            'questions' => $questions,
            'answered' => $answered->all(),
            'correct_answered' => $correct_answered,
        ];
    }

//    protected $with = ['users'];

    /**
     *Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
