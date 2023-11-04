<?php

// app/Rules/QuestionRules.php

namespace App\Rules;

use Illuminate\Validation\Rule;

class QuestionRules
{
    public static function commonRules(): array
    {
        $rules = [
            // Language 1
            'title' => ['required', 'string'],
            'details' => ['nullable', 'string'],
            'explain' => ['nullable', 'string'],

            // Language 2
            'title_two' => ['required', 'string'],
            'details_two' => ['nullable', 'string'],
            'explain_two' => ['nullable', 'string'],

            // Other fields
            'correct_answer' => ['required', 'string', Rule::in(['a', 'b', 'c', 'd'])],
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'topic_id' => ['required', 'numeric', 'exists:topics,id'],
            'category_id' => ['required', 'numeric', 'exists:categories,id', 'between:1,4']
        ];

        return array_merge(
            $rules,
            request()->input('_duplicate') ? ['_duplicate' => ['nullable', 'bool']] : []
        );
    }

    public static function optionsRules($array_keys): array
    {
        return [
            'options' => ['required', 'array', $array_keys],
            'options.*' => ['required', 'string'],
            'options_two' => ['required', 'array', $array_keys],
            'options_two.*' => ['required', 'string'],
        ];
    }

    public static function imageRules(): array
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ];
    }

    public static function getArrayKeys(): string
    {
        $questionOptions = request()->input('question_options');

        if ($questionOptions === '2') {
            return 'required_array_keys:a,b';
        }

        if ($questionOptions === '3') {
            return 'required_array_keys:a,b,c';
        }

        return 'required_array_keys:a,b,c,d';
    }

    public static function validationMessages(): array
    {
        return [
            'title.required' => 'Please, write the question name',
            'title_two.required' => 'Please, write the Chinese question name',
            'options.a.required' => 'Option A can not be blank',
            'options.b.required' => 'Option B can not be blank',
            'options.c.required' => 'Option C can not be blank',
            'options.d.required' => 'Option D can not be blank',
            'options_two.a.required' => 'Chinese Option A can not be blank',
            'options_two.b.required' => 'Chinese Option B can not be blank',
            'options_two.c.required' => 'Chinese Option C can not be blank',
            'options_two.d.required' => 'Chinese Option D can not be blank',
            'image' => 'Input file must be a valid image',
        ];
    }
}
