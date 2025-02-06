<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliationClassRoomResource\Pages;
use App\Filament\Resources\AffiliationClassRoomResource\RelationManagers;
use App\Models\AffiliationClassRoom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->getStateUsing(function ($record) {
                        return $record->period === 'D' ? 'صباحي' : 'مسائي';
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make(
                    [
                        Tables\Actions\EditAction::make(),
                        Tables\Actions\DeleteAction::make(),
                    ]
                )
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
        ];
    }
}
