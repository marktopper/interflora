<?php

namespace App\Http\Requests;

use App\Models\Node;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNodeRequest extends CreateNodeRequest
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
            'name' => 'string|max:255', // Varchar has max on 255 characters.

            // Validation for parent_id
            'parent_id' => ['nullable', 'exists:nodes,id', function ($field, $value, $error) {
                $updatingNode = request()->route()->parameters['node'] ?? null;
                $parentNode = Node::find($value);
                if (!$parentNode || !$updatingNode) {
                    return;
                }

                if ($parentNode->id === $updatingNode->id) {
                    return $error('Parent cannot be self.');
                }

                // Ensure that a parent is not related to this already, since that would leave a loop of parents.
                if ($parentNode->allParents()->pluck('id')->contains($updatingNode->id)) {
                    return $error('Three top cannot connect to root.');
                }
            }],

            // Validation for department
            'department' => 'nullable|string|max:255', // Varchar has max on 255 characters.

            // Validation for programming_language
            'programming_language' => 'nullable|in:php,js,c#,c++,python', // Only added options can be selected.
        ];
    }
}
