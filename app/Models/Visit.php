<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class Visit
 *
 * @property int                      $id
 * @property int                      $doctor_id
 * @property int                      $patient_id
 * @property string                   $status
 * @property int                      $time_interval_id
 * @property int                      $scheduleId
 *
 * @property \App\Models\TimeInterval $timeInterval
 * @property \App\Models\Schedule     $schedule
 *
 * @package App\Models
 */
class Visit extends Model
{
    public const VISIT_STATUS_WAIT = 'wait';
    public const VISIT_STATUS_FINISHED = 'finished';
    public $timestamps = false;
    public $fillable = [
        'doctor_id', 'patient_id', 'status', 'time_interval_id', 'schedule_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeInterval(): BelongsTo
    {
        return $this->belongsTo(TimeInterval::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
