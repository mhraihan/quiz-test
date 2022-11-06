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


    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('title', 'like', '%'.$search.'%');
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
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
}
