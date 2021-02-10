<?php


namespace App\Repositories;


use App\Models\CustomSchedule;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\TimeInterval;
use App\Models\Visit;

class SchedulesRepository
{

    /**
     * Получение расписания по доктору на определенные даты
     *
     * @param int $doctor_id
     * @param array $dates ['2020-01-01', '2020-02-12', etc.]
     *
     * @return array
     */
    public function getScheduleByDoctorId($doctor_id, $dates): array
    {
        $timeIntervals = TimeInterval::all();
        $visits = Visit::with('schedule')->where('doctor_id', $doctor_id)
            ->where('status', Visit::VISIT_STATUS_WAIT)
            ->whereHas('schedule', function ($query) use ($dates){
                return $query->whereIn('date', $dates);
            })
            ->get();

        $result = [];

        foreach ($dates as $date){
            foreach ($timeIntervals as $timeInterval){
                $available = true;
                foreach ($visits as $visit){
                    if( $timeInterval->id == $visit->time_interval_id &&
                        $visit->schedule->date == $date
                    ){
                        $available = false;
                        break;
                    }
                }
                // опять сущий кошмар в который лучше не лезть
                $schedule = Schedule::where('date', $date)->first();
                if(!$schedule){
                    break;
                }
                $customSchedules =  CustomSchedule::where('schedule_id', $schedule->id)->get('nonwork_time_interval_id')->toArray();
                $needle = ['nonwork_time_interval_id' => $timeInterval->id];
                if(in_array($needle, $customSchedules)){
                    $available = false;
                }

                $result[$date][] = [
                    'id' => $timeInterval->id,
                    'name' => $timeInterval->name,
                    'available' => $available
                ];
            }
        }

        return $result;
    }

    /**
     * Получение расписания по идентификатору доктора
     *
     * @param $id
     *
     * @return array
     */
    public function getDoctorScheduleById($id): array
    {
        $doctor = Doctor::find($id);
        $schedules = Schedule::where('doctor_id', $doctor->id)->where('date', '>=', date('Y-m-d'))->get('date');

        $result = [];
        foreach ($schedules as $schedule){
            $result[] = $schedule->date;
        }

        return $result;
    }

    /**
     * Создание нового расписания
     *
     * @param int $doctorId
     * @param     $dates
     * @param     $intervals
     *
     * @return bool
     */
    public function create(int $doctorId, $dates, $intervals): bool
    {
        try {
            foreach ($dates as $key => $date) {
                $dates[$key]['doctor_id'] = $doctorId;
                $schedule = Schedule::create($dates[$key]);
                foreach ($intervals as $intervalKey => $intervalId){
                    $intervals[$intervalKey]['schedule_id'] = $schedule->id;
                    CustomSchedule::create($intervals[$intervalKey]);
                }
            }
            return true;
        } catch (\Exception $exception){
            return false;
        }
    }

    public function getAvailableDatesDoctorId($doctor_id)
    {
        $schedules = Schedule::where('doctor_id', $doctor_id)->where('date', '>=', date('Y-m-d'))->get('date');

        $result = [];
        foreach ($schedules as $schedule){
            $result[] = $schedule->date;
        }

        return $result;
    }
}
