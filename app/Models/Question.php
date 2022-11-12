<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use QCod\ImageUp\HasImageUploads;


class Question extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasImageUploads;


    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'title',
        'details',
        'explain',
        'image',
        'options',
        'correct_answer',
        'is_active',
        'topic_id',
        'category_id'
    ];

    protected static array $imageFields = [
        'image' => [
            'path' => 'questions'
        ]
    ];


    public function scopeFilter($query, array $search, $trash): void
    {
        $query->when($search['search'] ?? null, function ($query, $input) {
            $query->where('title', 'like', '%'.$input.'%');
        })->when($trash ?? null, function ($query) {
            $query->onlyTrashed();
        });
    }

    public function scopeIndex($query, $trash = false): mixed
    {
       return $query->latest()
            ->filter(request()->only('search'),$trash)
            ->paginate(20)
            ->withQueryString()
            ->through(fn ($question) => [
                'id' => $question->id,
                'title' => $question->title
            ]);
    }

    /**
     *Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
