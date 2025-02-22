<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diploma extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dip_name',
        'dip_price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public static function getForm()
    {
        return [
            TextInput::make('dip_name')
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'يوجد دبلوم بهذا الاسم'
                ])
                ->label('اسم الدبلوم'),
            TextInput::make('dip_price')
                ->label('سعر الدبلوم')
                ->required()
                ->mask(RawJs::make('$money($input)'))
                ->stripCharacters(',')
                ->numeric(),
        ];
    }


    public function subjects()
    {
        // return $this->hasManyThrough(
        //     subject::class,
        //     DiplomaSubject::class,
        //     'diploma_id',
        //     'id' /*Primary Id in Subject Table */,
        //     'id',
        //     'subject_id' /*Subject Id in  Diploma Subject Table */
        // );

        return $this->belongsToMany(Subject::class)
            ->withPivot(['order']);
    }
}
