<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'العمليات الإدارية';

    protected static ?string  $modelLabel = 'الدورة';

    protected static ?string  $pluralModelLabel = 'الدورات';

    protected static ?string  $navigationLabel = 'الدورات';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('معلومات الدورة')
                    ->schema([
                        Forms\Components\TextInput::make('course_name')
                            ->label('اسم الدورة')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'توجد دورة بهذا الاسم'
                            ]),
                        Forms\Components\Select::make('teacher_id')
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
                        Forms\Components\Select::make('subject_id')
                            ->relationship('subject', 'sub_name')
                            ->label('المادة')
                            ->required()
                            ->validationMessages([
                                'required' => 'يجب ان تختر المادة',
                            ])->native(false)
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('diploma_id')
                            ->relationship('diploma', 'dip_name')
                            ->label('الدبلوم'),

                        // DatePicker::make('reg_date')
                        //     ->label('تاريخ التسجيل'),
                    ])->columns(2)
                    ->collapsible(),
                Section::make('مواعيد الدورة')
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
                        TimePicker::make('start_time')
                            ->seconds(false)
                            ->label('من')
                            ->live()
                            ->afterStateUpdated(function ($set) {
                                $set('class_room_id', null); // Clear the selected classroom when end_time changes
                            }),
                        TimePicker::make('end_time')
                            ->seconds(false)
                            ->label('الي')
                            ->live()
                            ->afterStateUpdated(function ($set) {
                                $set('class_room_id', null); // Clear the selected classroom when end_time changes
                            }),
                        Forms\Components\CheckboxList::make('days')
                            ->label('أيام الدورة')
                            ->options([
                                'saturday' => 'السبت',
                                'sunday' => 'الاحد',
                                'monday' => 'الاثنين',
                                'tuesday' => 'الثلاثاء',
                                'wednesday' => 'الاربعاء',
                                'thursday' => 'الخميس',
                                'friday' => 'الجمعة',
                            ])->columnSpanFull()
                            ->columns(7),
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
                                    $currentClassRoomId = $get('class_room_id'); // Get the currently selected classroom ID

                                    // If any of the required fields are missing, return the unmodified query
                                    if (!$start_date || !$end_date || !$start_time || !$end_time) {
                                        return $query;
                                    }

                                    // Query to find classrooms that are not occupied
                                    $query->whereNotIn('id', function ($subQuery) use ($start_date, $end_date, $start_time, $end_time) {
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
                                    'pending' =>   'معلق',
                                    'completed' =>   'إكتملت'

                                ]
                            )->default('pending')
                            ->native(false),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('course_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teacher.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('diploma.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reg_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('period')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
