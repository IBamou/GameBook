<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GameStoreRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:50|unique:games,name',
            'description' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'min_players' => 'required|integer|min:1',
            'max_players' => 'required|integer|gte:min_players',
            'spots' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string',
        ];
    }
}
