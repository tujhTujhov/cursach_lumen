<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Storage;

/**
 * Class Specialities
 *
 * @property int $id
 * @property string $name
 * @property string $specialityPicUrl
 *
 * @package App\Models
 */
class Specialities extends Model
{
    public $timestamps = false;

    public $hidden = [

    ];

    public function getSpecialityPicUrlAttribute($value): string
    {
        return url(Storage::url("specialities/$value"));
    }


}
