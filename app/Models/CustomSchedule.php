<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomSchedule
 *
 * @property int $int
 * @property int $doctor_id
 * @property int $schedule_id
 * @property int $nonwork_time_interval_id
 *
 * @package App\Models
 */
class CustomSchedule extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'doctor_id', 'schedule_id', 'nonwork_time_interval_id'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function nonWorkTimeInterval()
    {
        return $this->belongsTo(TimeInterval::class);
    }
}
