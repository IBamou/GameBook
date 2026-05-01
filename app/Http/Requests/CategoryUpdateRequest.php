<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
public function authorize(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;
        
        return [
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $categoryId,
            'description' => 'nullable|string',
        ];
    }
}
