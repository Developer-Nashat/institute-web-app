<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliationClassRoomResource\Pages;
use App\Filament\Resources\AffiliationClassRoomResource\RelationManagers;
use App\Models\AffiliationClassRoom;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;

class AffiliationClassRoomResource extends Resource
{
    protected static ?string $model = AffiliationClassRoom::class;


    protected static ?string $navigationGroup = 'العمليات الإدارية';

    protected static ?string  $modelLabel = 'تأجير قاعة';

    protected static ?string  $pluralModelLabel = 'تأجير القاعات';

    protected static ?string  $navigationLabel = 'تأجير القاعات';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(AffiliationClassRoom::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('affiliation.name')
                    ->label('اسم الجهة'),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->label('القاعة'),
                Tables\Columns\TextColumn::make('rent_price')
                    ->label('مبلغ الإيجار'),
                Tables\Columns\TextColumn::make('status')
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
                Tables\Columns\TextColumn::make('reg_date')
                    ->label('تاريخ التسجيل')
                    ->date(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('تاريخ البدء')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('تاريخ الإنتهاء')
                    ->date(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('توقيت البدء'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('توقيت الإنتهاء'),
                Tables\Columns\TextColumn::make('period')
                    ->label('الفترة')
                    ->formatStateUsing(fn($state) => $state === 'D' ? 'صباحي' : 'مسائي')
                    ->badge()
                    ->color(fn($state) => $state === 'D' ? 'info' : 'warning')
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (AffiliationClassRoom $record) {
                            $record->update(['status' => 'active']);
                        })
                        ->hidden(fn($record) => $record->status !== 'pending'),

                    Tables\Actions\EditAction::make()
                        ->color('primary'),

                    Tables\Actions\DeleteAction::make()
                        ->color('danger'),
                ])->label('الإجراءات')->tooltip('الإجراءات')
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
            'index' => Pages\ListAffiliationClassRooms::route('/'),
            'create' => Pages\CreateAffiliationClassRoom::route('/create'),
            'edit' => Pages\EditAffiliationClassRoom::route('/{record}/edit'),
            'view' => Pages\ViewAffiliationClassRoom::route('/{record}'),
        ];
    }
}
