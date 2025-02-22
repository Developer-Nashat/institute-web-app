<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('معلومات الدورة')
                    ->schema([
                        TextEntry::make('course_name')
                            ->label('اسم الدورة'),
                        TextEntry::make('teacher.ar_name')
                            ->label('المدرس'),
                        TextEntry::make('subject.sub_name')
                            ->label('المادة'),
                        TextEntry::make('diploma.dip_name')
                            ->label('الدبلوم'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('مواعيد الدورة')
                    ->schema([
                        TextEntry::make('start_date')
                            ->label('تاريخ البدء')
                            ->date(),
                        TextEntry::make('end_date')
                            ->label('تاريخ الإنتهاء')
                            ->date(),
                        TextEntry::make('start_time')
                            ->label('من')
                            ->time(),
                        TextEntry::make('end_time')
                            ->label('الي')
                            ->time(),
                        TextEntry::make('days')
                            ->label('أيام الدورة')
                            ->formatStateUsing(function ($state) {

                                // If $state is already an array, use it directly
                                if (is_array($state)) {
                                    $daysArray = $state;
                                }
                                // If $state is a JSON string, decode it
                                elseif (is_string($state) && json_decode($state, true) !== null) {
                                    $daysArray = json_decode($state, true);
                                }
                                // If $state is a serialized string, unserialize it
                                elseif (is_string($state) && @unserialize($state) !== false) {
                                    $daysArray = unserialize($state);
                                }
                                // If $state is invalid, return a fallback message
                                else {
                                    return 'غير متوفر';
                                }
                                // Convert days array to a readable string
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
                            })->columnSpanFull()

                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('القاعة')
                    ->schema([
                        TextEntry::make('classRoom.name')
                            ->label('القاعة'),
                    ])->collapsible(),

                Section::make('الحالة')
                    ->schema([
                        TextEntry::make('period')
                            ->label('الفترة')
                            ->formatStateUsing(function ($state) {
                                return match ($state) {
                                    'D' => 'صباحي',
                                    'N' => 'مسائي',
                                    default => $state,
                                };
                            }),
                        TextEntry::make('status')
                            ->label('الحالة')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'warning',
                                'active' => 'success',
                                'completed' => 'primary',
                                'cancelled' => 'danger',
                            })
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'pending' => 'معلق',
                                'active' => 'نشط',
                                'completed' => 'مكتمل',
                                'cancelled' => 'ملغي'
                            }),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('تعديل')
                ->color('primary'),

            Actions\Action::make('activate')
                ->label('تفعيل')
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->action(function () {
                    $this->record->update(['status' => 'active']);
                    $this->record->refresh();
                })
                ->visible(fn() => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->successNotificationTitle('تم التفعيل بنجاح'),

            Actions\Action::make('complete')
                ->label('إكمال')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->action(function () {
                    $this->record->update(['status' => 'completed']);
                    $this->record->refresh();
                })
                ->visible(fn() => $this->record->status === 'active')
                ->requiresConfirmation()
                ->successNotificationTitle('تم الإكمال بنجاح'),

            Actions\Action::make('cancel')
                ->label('إلغاء')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->action(function () {
                    $this->record->update(['status' => 'cancelled']);
                    $this->refresh();
                })
                ->visible(fn() => in_array($this->record->status, ['pending', 'active']))
                ->requiresConfirmation()
                ->successNotificationTitle('تم الإلغاء بنجاح'),

            // Actions\Action::make('show')
            //     ->action(function () {
            //         Notification::make()
            //             ->title('قاعات بحاجة إلى التفعيل')
            //             ->actions([
            //                 Action::make('view')
            //                     ->label('عرض القائمة')
            //                     ->button()
            //                     ->url(AffiliationClassRoomResource::getUrl('index')),
            //             ])->sendToDatabase(Auth::user());
            //     })
        ];
    }
}
