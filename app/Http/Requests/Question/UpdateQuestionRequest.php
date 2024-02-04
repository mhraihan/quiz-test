<?php

namespace App\Http\Requests\Question;

namespace App\Http\Requests\Question;

use App\Rules\QuestionRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin() || auth()->user()->isTeacher();
    }

    public function rules(): array
    {
        $array_keys = QuestionRules::getArrayKeys();
        return array_merge(
           QuestionRules::commonRules(),
            QuestionRules::optionsRules($array_keys),
            QuestionRules::imageRules()
        );
    }

    public function messages(): array
    {
        return QuestionRules::validationMessages();;
    }
}
