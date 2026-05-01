<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReservationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
public function authorize(): bool
    {
        $reservation = $this->route('reservation');
        return auth()->check() && (
            auth()->user()->role === 'admin' || 
            $reservation?->user_id === auth()->id()
        );
    }

    public function rules(): array
    {
        return [
            'table_id' => 'sometimes|required|exists:tables,id',
            'game_id' => 'nullable|exists:games,id',
            'date' => 'sometimes|required|date|after_or_equal:today',
            'start_time' => 'sometimes|required',
            'end_time' => 'sometimes|required|after:start_time',
            'spots' => 'sometimes|required|integer|min:1',
        ];
    }
}
