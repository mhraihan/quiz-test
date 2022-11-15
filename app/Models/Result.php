<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $appends = ['exam'];

    protected $casts = [
        'complete' => 'boolean',
//        'score' => 'float',
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
        'score',
        'start_time',
        'stop_time',
        'questions_answered',
        'total_questions'
    ];


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


    public function getDataFromQuestions($query)
    {
        $ids = array_column($query, 'id');
        $answers = array_column($query, 'answer');
        $questions = Question::query()->whereIn('id',$ids)->select('title','correct_answer','explain','options')->get();
        $answered = collect($questions)->pluck('correct_answer')->map(function ($item,$key) use($answers,$questions) {
            $answer = $item === $answers[$key];
            $questions[$key]['answer'] = $answers[$key];
            return $answer;
        });
        $correct_answer = count($answered->filter());

        return [
            'questions' =>  $questions,
            'answered' => $answered->all(),
            'score' => number_format(($correct_answer / count($answers)) * 100, 2),
            'correct_answer' => $correct_answer,
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
