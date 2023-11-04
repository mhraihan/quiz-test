<?php

namespace App\Http\Requests\Question;

use App\Rules\QuestionRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $array_keys = QuestionRules::getArrayKeys();
        dd($array_keys);

        return array_merge(
            QuestionRules::commonRules(),
            QuestionRules::optionsRules($array_keys),
            QuestionRules::imageRules()
        );
    }

    public function messages(): array
    {
        return QuestionRules::validationMessages();
    }
}
