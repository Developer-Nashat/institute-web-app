<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public static function getform()
    {
        return
            [
                TextInput::make('name')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'توجد جنسية بهذا الاسم'
                    ])
                    ->label('الجنسية')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ];
    }
}
