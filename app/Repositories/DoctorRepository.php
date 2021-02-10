<?php


namespace App\Repositories;


use App\Models\Doctor;

class DoctorRepository
{

    /**
     * СОздание доктора в базе
     *
     * @param $fields
     */
    public function create($fields)
    {
        Doctor::create($fields);
    }

    /**
     * Получение по специальности
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getBySpeciality($id)
    {
        return Doctor::with('speciality')->where('speciality_id', $id)->get();
    }

    /**
     * Получение по ID
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getById($id)
    {
        return Doctor::with('speciality')->findOrFail($id);
    }

    /**
     * Получение доктора по id пользователя
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getByUserId($userId)
    {
        return Doctor::where('user_id', $userId)->first();
    }

    /**
     * Получение профиля доктора со всеми зависимостями
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getProfile($id)
    {
        $doctor = Doctor::with([
            'user',
            'speciality',
            'visits',
            'visits.patient',
            'visits.timeInterval',
            'visits.schedule'
        ])->find($id);

        $visits = $doctor->visits;
        unset($doctor->visits);


        // после просмотра данного кода в украинском поезде начался сущий кошмар

        $preparedVisits = [];
        foreach ($visits as $visit) { // отфильтровывание визитов
            if($visit->schedule->date < date('Y-m-d')) continue;
            $preparedVisits[$visit->schedule->date][] = $visit;
        }

        foreach ($preparedVisits as $key => $preparedVisit) { // сортировка визитов по дате и времени
            usort($preparedVisits[$key], function ($a, $b){
                if($a->time_interval_id < $b->time_interval_id){
                    return -1;
                }else if($a->time_interval_id > $b->time_interval_id){
                    return 1;
                }
                return 0;
            });
        }

        $doctor->visits = $preparedVisits;

        return $doctor;

    }
}
