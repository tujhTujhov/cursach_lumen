<?php


namespace App\Repositories;


use App\Models\TimeInterval;

class TimeIntervalRepository
{

    /**
     * Получение всех временных интервалов
     *
     * @return \App\Models\TimeInterval[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return TimeInterval::all();
    }
}
