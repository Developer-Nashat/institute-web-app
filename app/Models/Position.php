<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position_name',
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
            TextInput::make('position_name')
                ->label('اسم الوظيفة')
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'توجد وظيفة بهذا الاسم'
                ])
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
