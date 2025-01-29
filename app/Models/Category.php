<?php

namespace App\Models;

use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cat_name',
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
            TextInput::make('cat_name')
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'يوجد تصنيف بهذا الاسم'
                ])
                ->label('اسم التصنيف')
                ->columnSpanFull()
        ];
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
