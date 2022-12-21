<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ResultTraits {
    public function results(User $user): LengthAwarePaginator
    {
       return $user->results()
            ->latest()
            ->select('id', 'complete', 'correct_answered', 'total_questions', 'stop_time', 'start_time', 'created_at')
            ->paginate(30)
            ->withQueryString()
            ->through(fn($result) => [
                'id' => $result->id,
                'complete' => $result->complete,
                'total_questions' => $result->total_questions,
                'score' => $result->score,
                'exam' => $result->exam['how_long'],
            ]);
    }

    public function exam(User $user): User
    {
        return $user->loadSum('results', 'total_questions')->loadSum('results', 'correct_answered');
    }
}
