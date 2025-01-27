<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ar_name',
        'en_name',
        'date_of_birth',
        'person_gender',
        'person_email',
        'first_phone',
        'second_phone',
        'person_address',
        'nationalty_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'nationalty_id' => 'integer',
    ];

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function nationalty(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }
}
