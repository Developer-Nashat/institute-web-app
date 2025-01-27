<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_name',
        'sub_price',
        'DurationType',
        'TotalHours',
        'Totaldays',
        'cat_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sub_price' => 'decimal',
        'TotalHours' => 'integer',
        'Totaldays' => 'integer',
        'cat_id' => 'integer',
    ];

    public function diplomas(): BelongsToMany
    {
        return $this->belongsToMany(Diploma::class);
    }

    public function cat(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
