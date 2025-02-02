<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NationalityResource\Pages;
use App\Filament\Resources\NationalityResource\RelationManagers;
use App\Models\Nationality;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NationalityResource extends Resource
{
    protected static ?string $model = Nationality::class;

    protected static ?string $navigationGroup = 'المدخلات الأولية';

    protected static ?string  $modelLabel = 'الجنسية';

    protected static ?string  $pluralModelLabel = 'الجنسيات';

    protected static ?string  $navigationLabel = 'الجنسيات';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Nationality::getForm);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الجنسية'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('lg'),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNationalities::route('/'),
            // 'create' => Pages\CreateNationality::route('/create'),
            // 'edit' => Pages\EditNationality::route('/{record}/edit'),
        ];
    }
}
