<?php


namespace App\Repositories;


use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Visit;

class VisitsRepository
{

    /**
     * Создание нового визита
     *
     * @param array $validated
     *
     * @return bool
     */
    public function create(array $validated): bool
    {
        if (
            !$schedule = Schedule::where('date', $validated['date'])
            ->where('doctor_id', $validated['doctor_id'])->first()
        ){
            return false;
        }

        $validated['schedule_id'] = $schedule->id;
        $validated['status'] = Visit::VISIT_STATUS_WAIT;
        $validated['patient_id'] = Patient::where('user_id', Auth()->user()->id)->first()->id;

        $visit = new Visit($validated);
        return $visit->save();
    }
}
