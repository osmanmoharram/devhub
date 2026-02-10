<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscussionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
            ],
            'content' => [
                'required',
                'string',
                'min:10',
            ],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where('is_active', true),
            ],
        ];
    }

    /**
     * Get the custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Discussion title is required.',
            'title.min' => 'Discussion title must be at least 3 characters.',
            'title.max' => 'Discussion title may not be greater than 255 characters.',
            'content.required' => 'Discussion content is required.',
            'content.min' => 'Discussion content must be at least 10 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category is not valid.',
        ];
    }
}
