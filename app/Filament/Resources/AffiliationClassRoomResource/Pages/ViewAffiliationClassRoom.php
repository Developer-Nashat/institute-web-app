<?php

namespace App\Filament\Resources\AffiliationclassRoomResource\Pages;

use App\Filament\Resources\AffiliationClassRoomResource;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewAffiliationClassRoom extends ViewRecord
{
    protected static string $resource = AffiliationClassRoomResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('معلومات التأجير الأساسية')
                    ->schema([
                        TextEntry::make('affiliation.name')
                            ->label('اسم الجهة'),
                        TextEntry::make('classRoom.name')
                            ->label('القاعة'),
                        TextEntry::make('rent_price')
                            ->label('مبلغ الإيجار')
                            ->numeric(),
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
                    ])->columns(2),

                Section::make('التفاصيل الزمنية')
                    ->schema([
                        TextEntry::make('reg_date')
                            ->label('تاريخ التسجيل')
                            ->date(),
                        TextEntry::make('start_date')
                            ->label('تاريخ البدء')
                            ->date(),
                        TextEntry::make('end_date')
                            ->label('تاريخ الإنتهاء')
                            ->date(),
                        TextEntry::make('start_time')
                            ->label('توقيت البدء'),
                        TextEntry::make('end_time')
                            ->label('توقيت الإنتهاء'),
                        TextEntry::make('period')
                            ->label('الفترة')
                            ->formatStateUsing(fn($state) => $state === 'D' ? 'صباحي' : 'مسائي')
                            ->badge()
                            ->color(fn($state) => $state === 'D' ? 'info' : 'warning'),
                    ])->columns(3)
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
                    $this->refresh();
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
                    $this->refresh();
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
