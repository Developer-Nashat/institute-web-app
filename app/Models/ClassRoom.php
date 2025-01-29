<?php

namespace App\Models;

use App\Enums\ClassRoomStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'capacity' => 'integer',
        'room_no' => 'integer',
    ];

    public function affiliationClassRooms(): HasMany
    {
        return $this->hasMany(AffiliationClassRoom::class);
    }
}
