<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'table_id' => 'required|exists:tables,id',
            'game_id' => 'nullable|exists:games,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'spots' => 'required|integer|min:1',
        ];
    }
}
