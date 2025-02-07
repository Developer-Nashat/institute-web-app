<?php

namespace App\Models;

use App\Enums\AffiliationClassRoomStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Get;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AffiliationClassRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'affiliation_id' => 'integer',
        'class_room_id' => 'integer',
        'reg_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];


    public static function getForm()
    {
        return
            [
                Section::make()
                    ->schema(
                        [
                            Select::make('affiliation_id')
                                ->label('اسم الجهة')
                                ->relationship('affiliation', 'name')
                                ->required(),
                            DatePicker::make('reg_date')
                                ->label('تاريخ التسجيل'),

                            Fieldset::make('فترة التأجير')
                                ->schema([
                                    DatePicker::make('start_date')
                                        ->label('تاريخ البدء')
                                        ->live()
                                        ->afterStateUpdated(function ($set) {
                                            $set('class_room_id', null); // Clear the selected classroom when end_time changes
                                        }),
                                    DatePicker::make('end_date')
                                        ->label('تاريخ الإنتهاء')
                                        ->live()
                                        ->afterStateUpdated(function ($set) {
                                            $set('class_room_id', null); // Clear the selected classroom when end_time changes
                                        }),
                                ]),

                            Fieldset::make('التوقيت')
                                ->schema([
                                    TimePicker::make('start_time')
                                        ->label('من')
                                        ->live()
                                        ->afterStateUpdated(function ($set) {
                                            $set('class_room_id', null); // Clear the selected classroom when end_time changes
                                        }),
                                    TimePicker::make('end_time')
                                        ->label('الي')
                                        ->live()
                                        ->afterStateUpdated(function ($set) {
                                            $set('class_room_id', null); // Clear the selected classroom when end_time changes
                                        }),
                                ]),


                            Select::make('class_room_id')
                                ->label('القاعة')
                                ->searchable()
                                ->preload()
                                ->relationship(
                                    'classRoom', // Relationship name
                                    'name',      // Display column
                                    modifyQueryUsing: function ($query, Get $get) {
                                        $start_date = $get('start_date');
                                        $end_date = $get('end_date');
                                        $start_time = $get('start_time');
                                        $end_time = $get('end_time');

                                        // If any of the required fields are missing, return the unmodified query
                                        if (!$start_date || !$end_date || !$start_time || !$end_time) {
                                            return $query;
                                        }

                                        // Query to find classrooms that are not occupied
                                        return $query->whereNotIn('id', function ($subQuery) use ($start_date, $end_date, $start_time, $end_time) {
                                            $subQuery->select('class_room_id')
                                                ->from('affiliation_class_rooms')
                                                ->whereIn('status', ['active', 'pending'])
                                                ->where(function ($q) use ($start_date, $end_date, $start_time, $end_time) {
                                                    // Check for overlapping schedules
                                                    $q->where(function ($q) use ($start_date, $end_date, $start_time, $end_time) {
                                                        $q->whereDate('start_date', '<=', $end_date)
                                                            ->whereDate('end_date', '>=', $start_date)
                                                            ->whereTime('start_time', '<=', $end_time)
                                                            ->whereTime('end_time', '>=', $start_time);
                                                    });
                                                });
                                        });

                                        $subQuery->union(
                                            DB::table('courses')
                                                ->select('class_room_id')
                                                ->whereIn('status', ['active', 'reserved'])
                                                ->where(function ($q) use ($start_date, $end_date, $start_time, $end_time) {
                                                    // Check for overlapping courses
                                                    $q->whereDate('start_date', '<=', $end_date)
                                                        ->whereDate('end_date', '>=', $start_date)
                                                        ->whereTime('start_time', '<=', $end_time)
                                                        ->whereTime('end_time', '>=', $start_time);
                                                })
                                        );
                                    }
                                )->required(),
                            TextInput::make('rent_price')
                                ->label('مبلغ الإيجار')
                                ->mask(RawJs::make('$money($input)'))
                                ->stripCharacters(',')
                                ->numeric(),
                            Select::make('period')
                                ->label('الفترة')
                                ->options(
                                    [
                                        'D' => 'صباحي',
                                        'N' => 'مسائي'
                                    ]
                                )->native(false),
                            Select::make('status')
                                ->label('الحالة')
                                ->options(
                                    [
                                        'panding' =>   'محجوز',
                                        'completed' =>   'إكتملت'

                                    ]
                                )->default('panding')
                                ->native(false),
                        ]
                    )->columns(2)
            ];
    }

    public function affiliation(): BelongsTo
    {
        return $this->belongsTo(Affiliation::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
