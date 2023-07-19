<?php

namespace App\Http\Requests\Topic;

use App\Models\Topic;
use App\Rules\Trimmed;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTopicRequest extends FormRequest
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
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                new Trimmed(),
                Rule::unique(Topic::class)->ignore(request()->id), // Ensures the title is unique in the 'topics' table
            ]
        ];
    }
}
