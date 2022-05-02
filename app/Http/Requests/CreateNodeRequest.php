<?php

namespace App\Http\Requests;

use App\Models\Node;
use Illuminate\Foundation\Http\FormRequest;

class CreateNodeRequest extends FormRequest
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
            // Validation rules for name
            'name' => 'required|string|max:255', // Varchar has max on 255 characters.

            // Validation for parent_id
            'parent_id' => ['nullable', 'exists:nodes,id'],

            // Validation for department
            'department' => 'nullable|string|max:255', // Varchar has max on 255 characters.

            // Validation for programming_language
            'programming_language' => 'nullable|in:php,js,c#,c++,python', // Only added options can be selected.
        ];
    }
}
