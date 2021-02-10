<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Roles
 *
 * @property int $id
 * @property string $name
 *
 * @package App\Models
 */
class Roles extends Model
{
    public $timestamps = false;

    public const DOCTOR_ROLE_ID = 1;
    public const PATIENT_ROLE_ID = 2;
}
