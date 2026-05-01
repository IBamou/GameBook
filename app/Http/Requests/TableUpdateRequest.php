<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TableUpdateRequest extends FormRequest
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
        $tableId = $this->route('table')?->id;
        
        return [
            'capacity' => 'sometimes|required|integer|min:1',
            'reference' => 'sometimes|required|string|max:50|unique:tables,reference,' . $tableId,
        ];
    }
}
