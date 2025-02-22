<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
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
        'diploma_id',
        'class_room_id',
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
        'diploma_id' => 'integer',
        'class_room_id' => 'integer',
        'reg_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        // 'days' => 'array',
    ];

    protected static function validateDays(array $selectedDays, string $startDate, string $endDate): array
    {
        $invalidDays = [];

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        foreach ($selectedDays as $day) {
            $dayOfWeek = self::getDayOfWeek($day);

            // Check if the selected day exists within the date range
            $date = $startDate->copy();
            $found = false;

            while ($date->lte($endDate)) {
                if ($date->dayOfWeek === $dayOfWeek) {
                    $found = true;
                    break;
                }
                $date->addDay();
            }

            if (!$found) {
                $invalidDays[] = $day;
            }
        }

        return $invalidDays;
    }

    protected static function getDayOfWeek(string $day): int
    {
        return match ($day) {
            'saturday' => Carbon::SATURDAY,
            'sunday' => Carbon::SUNDAY,
            'monday' => Carbon::MONDAY,
            'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY,
            'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY,
            default => throw new \InvalidArgumentException("Invalid day: $day"),
        };
    }

    public static function getForm()
    {
        return [
            Section::make('معلومات الدورة')
                ->schema([
                    TextInput::make('course_name')
                        ->label('اسم الدورة')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'توجد دورة بهذا الاسم'
                        ]),
                    Select::make('teacher_id')
                        ->relationship(
                            'teacher',
                            'ar_name',
                            modifyQueryUsing: fn(Builder $query) => $query->where('is_teacher', true),
                        )
                        ->required()
                        ->validationMessages([
                            'required' => 'يجب ان تختر المدرس',
                        ])->native(false)
                        ->label('المدرس')
                        ->searchable()
                        ->preload(),
                    Select::make('subject_id')
                        ->relationship('subject', 'sub_name')
                        ->label('المادة')
                        ->required()
                        ->validationMessages([
                            'required' => 'يجب ان تختر المادة',
                        ])->native(false)
                        ->searchable()
                        ->preload(),
                    Select::make('diploma_id')
                        ->relationship('diploma', 'dip_name')
                        ->label('الدبلوم'),
                ])->columns(2)
                ->collapsible(),

            Section::make('مواعيد الدورة')
                ->schema([
                    DatePicker::make('start_date')
                        ->label('تاريخ البدء')
                        ->live()
                        ->afterStateUpdated(function ($set) {
                            $set('class_room_id', null); // Clear the selected classroom when start_date changes
                        }),
                    DatePicker::make('end_date')
                        ->label('تاريخ الإنتهاء')
                        ->live()
                        ->afterStateUpdated(function ($set) {
                            $set('class_room_id', null); // Clear the selected classroom when end_date changes
                        }),
                    TimePicker::make('start_time')
                        ->seconds(false)
                        ->label('من')
                        ->live()
                        ->afterStateUpdated(function ($set) {
                            $set('class_room_id', null); // Clear the selected classroom when start_time changes
                        }),
                    TimePicker::make('end_time')
                        ->seconds(false)
                        ->label('الي')
                        ->live()
                        ->afterStateUpdated(function ($set) {
                            $set('class_room_id', null); // Clear the selected classroom when end_time changes
                        }),
                    CheckboxList::make('days')
                        ->label('أيام الدورة')
                        ->options([
                            'saturday' => 'السبت',
                            'sunday' => 'الاحد',
                            'monday' => 'الاثنين',
                            'tuesday' => 'الثلاثاء',
                            'wednesday' => 'الاربعاء',
                            'thursday' => 'الخميس',
                            'friday' => 'الجمعة',
                        ])
                        ->columns(7)
                        ->columnSpanFull()
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            // Validate selected days based on the date range
                            $startDate = $get('start_date');
                            $endDate = $get('end_date');

                            if ($startDate && $endDate) {
                                $invalidDays = self::validateDays($state, $startDate, $endDate);
                                if (!empty($invalidDays)) {
                                    // Show an error message or clear invalid days
                                    $set('days', array_diff($state, $invalidDays));

                                    Notification::make()
                                        ->title('أيام غير صالحة')
                                        ->body('بعض الأيام المحددة لا تقع ضمن نطاق التاريخ المحدد.')
                                        ->danger()
                                        ->send();
                                }
                            }
                        }),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('القاعة')
                ->schema([
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
                                $days = $get('days'); // Get the selected days
                                $currentClassRoomId = $get('class_room_id'); // Get the currently selected classroom ID

                                // If any of the required fields are missing, return the unmodified query
                                if (!$start_date || !$end_date || !$start_time || !$end_time || empty($days)) {
                                    return $query;
                                }

                                // Convert selected days to PostgreSQL DOW values (0=Sunday, 6=Saturday)
                                $selectedDays = array_map(function ($day) {
                                    return match ($day) {
                                        'saturday' => 6,
                                        'sunday' => 0,
                                        'monday' => 1,
                                        'tuesday' => 2,
                                        'wednesday' => 3,
                                        'thursday' => 4,
                                        'friday' => 5,
                                        default => throw new \InvalidArgumentException("Invalid day: $day"),
                                    };
                                }, $days);

                                // Query to find classrooms that are not occupied on the selected days, date, and time
                                $query->whereNotIn('id', function ($subQuery) use ($start_date, $end_date, $start_time, $end_time, $selectedDays) {
                                    $subQuery->select('class_room_id')
                                        ->from('affiliation_class_rooms') // Replace with your table name
                                        ->whereIn('status', ['active', 'pending'])
                                        ->where(function ($q) use ($start_date, $end_date, $start_time, $end_time, $selectedDays) {
                                            // Check for overlapping schedules on the selected days
                                            $q->whereDate('start_date', '<=', $end_date)
                                                ->whereDate('end_date', '>=', $start_date)
                                                ->whereTime('start_time', '<=', $end_time)
                                                ->whereTime('end_time', '>=', $start_time)
                                                ->whereIn(DB::raw('EXTRACT(DOW FROM start_date)'), $selectedDays); // Filter by selected days
                                        });
                                });

                                // Include the currently selected classroom in the results
                                if ($currentClassRoomId) {
                                    $query->orWhere('id', $currentClassRoomId);
                                }

                                return $query;
                            }
                        )
                        ->required(),
                ]),

            Section::make('الحالة')
                ->schema([
                    Select::make('period')
                        ->label('الفترة')
                        ->options([
                            'D' => 'صباحي',
                            'N' => 'مسائي'
                        ])->native(false),
                    Select::make('status')
                        ->label('الحالة')
                        ->options([
                            'pending' => 'معلق',
                            'completed' => 'إكتملت'
                        ])->default('pending')
                        ->native(false),
                ])
                ->columns(2)
                ->collapsible(),
        ];
    }
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

    public function diploma(): BelongsTo
    {
        return $this->belongsTo(Diploma::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function studentCourseResults(): HasMany
    {
        return $this->hasMany(StudentCourseResult::class);
    }
}
