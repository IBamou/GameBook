<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GameUpdateRequest extends FormRequest
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
        $gameId = $this->route('game')?->id;
        
        return [
            'name' => 'sometimes|required|string|max:50|unique:games,name,' . $gameId,
            'description' => 'sometimes|required|string',
            'difficulty' => 'sometimes|required|in:easy,medium,hard',
            'min_players' => 'sometimes|required|integer|min:1',
            'max_players' => 'sometimes|required|integer|gte:min_players',
            'spots' => 'sometimes|required|integer|min:1',
            'duration' => 'sometimes|required|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'sometimes|required|numeric|min:0',
            'image_url' => 'nullable|string',
        ];
    }
}
