<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliationClassRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'affiliation_id',
        'class_room_id',
        'rent_price',
        'reg_date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'period',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'affiliation_id' => 'integer',
        'class_room_id' => 'integer',
        'rent_price' => 'decimal',
        'reg_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function affiliation(): BelongsTo
    {
        return $this->belongsTo(Affiliation::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
