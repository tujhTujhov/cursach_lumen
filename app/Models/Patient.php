<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Patient
 *
 * @property int    $id
 * @property string $name
 * @property string $surname
 * @property string $middlename
 * @property int    $userId
 *
 * @package App\Models
 */
class Patient extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'surname', 'middlename', 'user_id'
    ];

    public function visit(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
