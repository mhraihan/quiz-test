<?php

namespace App\Http\Requests\Result;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'class_id' => ['nullable'],
            'complete' => ['nullable', 'boolean'],
            'start_time' => ['required','date'],
            'stop_time' => ['required','date'],
            'questions_answered' => ['required', 'array'],
            'questions_answered.*.id' => ['required', 'numeric'],
            'questions_answered.*.answer' => ['required', 'string'],
            'total_questions' => ['required', 'numeric'],
        ];
    }
}
