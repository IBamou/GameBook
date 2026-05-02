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

    public function after(): array
    {
        return [
            function ($validator) {
                // Use input values or fallback to the existing reservation values
                $reservation = $this->route('reservation');
                $tableId = $this->input('table_id', $reservation?->table_id);
                $date = $this->input('date', $reservation?->date);
                $startTime = $this->input('start_time', $reservation?->start_time);
                $endTime = $this->input('end_time', $reservation?->end_time);

                if ($tableId && $date && $startTime && $endTime) {
                    $conflict = \App\Models\Reservation::where('table_id', $tableId)
                        ->where('date', $date)
                        ->whereNotIn('status', ['cancelled'])
                        ->when($reservation, function ($query) use ($reservation) {
                            $query->where('id', '!=', $reservation->id);
                        })
                        ->where(function ($query) use ($startTime, $endTime) {
                            $query->whereTime('start_time', '<', $endTime)
                                  ->whereTime('end_time', '>', $startTime);
                        })
                        ->exists();

                    if ($conflict) {
                        $validator->errors()->add('time', 'This table is already reserved during the requested time slot.');
                    }

                    $hasActiveSession = \App\Models\Reservation::where('table_id', $tableId)
                        ->where('date', $date)
                        ->where('status', 'confirmed')
                        ->when($reservation, function ($query) use ($reservation) {
                            $query->where('id', '!=', $reservation->id);
                        })
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
