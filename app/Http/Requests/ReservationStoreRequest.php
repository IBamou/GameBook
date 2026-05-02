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

    public function after(): array
    {
        return [
            function ($validator) {
                if ($this->table_id && $this->date && $this->start_time && $this->end_time) {
                    $conflict = \App\Models\Reservation::where('table_id', $this->table_id)
                        ->where('date', $this->date)
                        ->whereNotIn('status', ['cancelled'])
                        ->where(function ($query) {
                            $query->whereTime('start_time', '<', $this->end_time)
                                  ->whereTime('end_time', '>', $this->start_time);
                        })
                        ->exists();

                    if ($conflict) {
                        $validator->errors()->add('time', 'This table is already reserved during the requested time slot.');
                    }

                    $hasActiveSession = \App\Models\Reservation::where('table_id', $this->table_id)
                        ->where('date', $this->date)
                        ->where('status', 'confirmed')
                        ->whereHas('sessions', function ($q) {
                            $q->where('status', 'active');
                        })
                        ->exists();

                    if ($hasActiveSession) {
                        $validator->errors()->add('time', 'This table is currently in use. Please choose another time slot.');
                    }
                }
            }
        ];
    }
}
