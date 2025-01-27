<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompleteMasterCourse extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_name',
        'teacher_id',
        'subject_id',
        'class_room_id',
        'diploma_id',
        'reg_date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'period',
        'days',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'teacher_id' => 'integer',
        'subject_id' => 'integer',
        'class_room_id' => 'integer',
        'diploma_id' => 'integer',
        'reg_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'days' => 'array',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function diploma(): BelongsTo
    {
        return $this->belongsTo(Diploma::class);
    }
}
