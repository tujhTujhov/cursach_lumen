<?php


namespace App\Repositories;


use App\Models\Specialities;

class SpecialitiesRepository
{

    /**
     * Получение всех специальностей
     *
     * @return \App\Models\Specialities[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Specialities::all();
    }
}
