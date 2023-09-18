<?php

namespace App\Http\Requests\Question;

use Illuminate\Validation\Rule;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $array_keys = 'required_array_keys:a,b,c,d';
        if (request()->input('question_options') === '2'){
              $array_keys = 'required_array_keys:a,b';
        }
          if (request()->input('question_options') === '3'){
              $array_keys = 'required_array_keys:a,b,c';
        }

        return [
            // Language 1
            'title' => ['required', 'string'],
            'details' => ['required', 'string'],
            'explain' => ['nullable', 'string'],
            'options' => ['required', 'array', $array_keys],
            'options.*' => ['required', 'string'],

            // Language 2
            'title_two' => ['required', 'string'],
            'details_two' => ['required', 'string'],
            'explain_two' => ['nullable', 'string'],
            'options_two' => ['required', 'array', $array_keys],
            'options_two.*' => ['required', 'string'],

            // Other fields
            'correct_answer' => ['required', 'string', Rule::in(['a', 'b', 'c', 'd'])],
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
            'topic_id' => ['required', 'numeric', 'exists:topics,id'],
            'category_id' => ['required', 'numeric', 'exists:categories,id', 'between:1,4']
        ];
    }

    public function messages()
    {
        return [
            'title' => 'Please, write the question name',
            'details' => 'Please, Describe about the question',
            'options.a' => "Option A can not be blank",
            'options.b' => "Option B can not be blank",
            'options.c' => "Option C can not be blank",
            'options.d' => "Option C can not be blank",
            'image' => "Input file must be a valid image"
        ];
    }
}
