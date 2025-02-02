<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecializationResource\Pages;
use App\Filament\Resources\SpecializationResource\RelationManagers;
use App\Models\Specialization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecializationResource extends Resource
{
    protected static ?string $model = Specialization::class;

    protected static ?string $navigationGroup = 'المدخلات الأولية';

    protected static ?string  $modelLabel = 'التخصص';

    protected static ?string  $pluralModelLabel = 'التخصصات';

    protected static ?string  $navigationLabel = 'التخصصات';

    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'عدد التخصصات';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Specialization::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('specialization_name')
                    ->label('اسم التخصص'),

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
            'index' => Pages\ListSpecializations::route('/'),
            // 'create' => Pages\CreateSpecialization::route('/create'),
            // 'edit' => Pages\EditSpecialization::route('/{record}/edit'),
        ];
    }
}
