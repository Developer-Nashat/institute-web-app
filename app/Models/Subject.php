<?php

namespace App\Models;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_name',
        'sub_price',
        'duration_type',
        'total_hours',
        'total_days',
        'category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
    ];

    public static function getForm()
    {
        return
            [
                TextInput::make('sub_name')->label('اسم المادة')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('sub_price')->label('سعر المادة')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric(),
                TextInput::make('total_days')->label('عدد الأيام')
                    ->numeric(),
                TextInput::make('total_hours')->label('عدد الساعة')
                    ->numeric(),

                Select::make('category_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('category', 'cat_name')
                    ->label('التصنيف')
                    ->columnSpanFull()
                    ->createOptionForm(Category::getForm())
                    ->EditOptionForm(Category::getForm())
            ];
    }

    public function subjectsOfDiploma()
    {
        return $this->hasMany(DiplomaSubject::class);
    }

    public function diplomas()
    {
        return $this->belongsToMany(Diploma::class)
            ->withPivot(['order'])
            ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
