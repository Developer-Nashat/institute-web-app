<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'specialization_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    static function getForm()
    {

        return
            [
                TextInput::make('specialization_name')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'يوجد تخصص بهذا الاسم'
                    ])
                    ->label('اسم التخصص')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ];
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class);
    }
}
