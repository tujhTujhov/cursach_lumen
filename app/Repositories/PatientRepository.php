<?php


namespace App\Repositories;


use App\Models\Patient;

class PatientRepository
{

    /**
     * Создание пациента
     *
     * @param $fields
     */
    public function create($fields)
    {
        Patient::create($fields);
    }

    /**
     * Получение пациента по ИД пользователя системы
     *
     * @param $userId
     *
     * @return mixed
     */
    public function getByUserId($userId)
    {
        return Patient::where('user_id', $userId)->first();
    }

    /**
     * Получение профиля по ID пациента с зависимостями
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getProfileById(int $id)
    {
        return Patient::with([
            'visit.schedule' => function ($query) {
                $query->select(['date', 'id']);
            },
            'visit.doctor' => function ($query) {
                $query->select(['name', 'surname', 'middlename', 'speciality_id', 'id']);
            },
            'visit.doctor.speciality' => function ($query) {
                $query->select(['name', 'id']);
            },
            'visit.timeInterval' => function ($query) {
                $query->select(['name', 'id']);
            },
            'user' => function ($query) {
                $query->select(['email', 'id']);
            },
        ])->find($id);
    }

}
