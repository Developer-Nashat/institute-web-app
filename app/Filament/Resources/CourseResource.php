<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\StudentRelationManager;
use App\Models\Course;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'العمليات الإدارية';

    protected static ?string $modelLabel = 'الدورة';

    protected static ?string $pluralModelLabel = 'الدورات';

    protected static ?string $navigationLabel = 'الدورات';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->schema(Course::getForm());
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // Course Name
                TextColumn::make('course_name')
                    ->label('اسم الدورة'),

                // Teacher Name (from relationship)
                TextColumn::make('teacher.ar_name')
                    ->label('المدرس')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Subject Name (from relationship)
                TextColumn::make('subject.sub_name')
                    ->label('المادة')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Diploma Name (from relationship)
                TextColumn::make('diploma.dip_name')
                    ->label('الدبلوم')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Classroom Name (from relationship)
                TextColumn::make('classRoom.name')
                    ->label('القاعة'),

                // Start Date
                TextColumn::make('start_date')
                    ->label('تاريخ البدء')
                    ->date(),

                // End Date
                TextColumn::make('end_date')
                    ->label('تاريخ الإنتهاء')
                    ->date(),

                // Start Time
                TextColumn::make('start_time')
                    ->label('من')
                    ->time(),

                // End Time
                TextColumn::make('end_time')
                    ->label('الي')
                    ->time(),

                // Days (formatted as a string)
                TextColumn::make('days')
                    ->label('أيام الدورة')
                    ->formatStateUsing(function ($state) {
                        // If $state is already an array, use it directly
                        if (is_array($state)) {
                            $daysArray = $state;
                        } // If $state is a JSON string, decode it
                        elseif (is_string($state) && json_decode($state, true) !== null) {
                            $daysArray = json_decode($state, true);
                        } // If $state is a serialized string, unserialize it
                        elseif (is_string($state) && @unserialize($state) !== false) {
                            $daysArray = unserialize($state);
                        } // If $state is invalid, return a fallback message
                        else {
                            return 'غير متوفر';
                        }

                        // Ensure $daysArray is an array
                        if (!is_array($daysArray)) {
                            return 'غير متوفر';
                        }

                        // Define the days map with the desired order
                        $daysMap = [
                            'saturday' => 'السبت',
                            'sunday' => 'الاحد',
                            'monday' => 'الاثنين',
                            'tuesday' => 'الثلاثاء',
                            'wednesday' => 'الاربعاء',
                            'thursday' => 'الخميس',
                            'friday' => 'الجمعة',
                        ];

                        // Sort the $daysArray based on the order of keys in $daysMap
                        $sortedDays = array_intersect_key($daysMap, array_flip($daysArray));

                        // Convert the sorted days array to a readable string
                        return implode(', ', $sortedDays);
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                // Status (formatted as معلق/إكتملت)
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'معلق',
                        'active' => 'نشط',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي'
                    }),

                // Period (formatted as صباحي/مسائي)
                TextColumn::make('period')
                    ->label('الفترة')
                    ->label('الفترة')
                    ->formatStateUsing(fn($state) => $state === 'D' ? 'صباحي' : 'مسائي')
                    ->badge()
                    ->color(fn($state) => $state === 'D' ? 'info' : 'warning'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'محجوز',
                        'active' => 'نشط',
                        'completed' => 'مكتمل',
                        'cancelled' => 'ملغي'
                    ])->native(false),

                Tables\Filters\SelectFilter::make('period')
                    ->label('الفترة')
                    ->options([
                        'D' => 'صباحي',
                        'E' => 'مسائي'
                    ])->native(false)
            ])
            ->actions([
                // Add actions like Edit, Delete, etc.
                ActionsActionGroup::make(
                    [
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\Action::make('activate')
                            ->label('تفعيل')
                            ->icon('heroicon-o-check-circle')
                            ->color('success')
                            ->action(function (Course $record) {
                                $record->update(['status' => 'active']);
                            })
                            ->hidden(fn($record) => $record->status !== 'pending'),

                        Tables\Actions\EditAction::make()
                            ->color('primary'),

                        Tables\Actions\DeleteAction::make()
                            ->color('danger'),
                    ]
                )->label('الإجراءات')->tooltip('الإجراءات')
            ])
            ->bulkActions([
                // Add bulk actions if needed
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
            'view' => Pages\ViewCourse::route('/{record}'),
        ];
    }
}
