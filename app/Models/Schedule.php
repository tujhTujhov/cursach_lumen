<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Schedule
 *
 * @property int $id
 * @property int $doctorId
 * @property string $date
 *
 * @package App\Models
 */
class Schedule extends Model
{
    public $timestamps = false;

    public $fillable = [
        'doctor_id', 'date'
    ];
}
