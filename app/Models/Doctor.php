<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Storage;

/**
 * Class Doctor
 *
 * @property int                 $id
 * @property string              $name
 * @property string              $surname
 * @property string              $middlenameR
 * @property int                 $specialityId
 * @property int                 $userId
 * @property string              $avatarUrl
 *
 * @property \App\Models\Visit[] $visits
 *
 * @package App\Models
 */
class Doctor extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'surname', 'middlename', 'speciality_id', 'user_id'
    ];

    protected $hidden = [
        'speciality_id', 'user_id'
    ];

    /**
     * @param $value
     *
     * @return string
     */
    public function getAvatarUrlAttribute($value): string
    {
        return url(Storage::url("avatars/$value"));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Specialities::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
