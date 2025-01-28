<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiplomaResource\Pages;
use App\Filament\Resources\DiplomaResource\RelationManagers;
use App\Filament\Resources\DiplomaResource\RelationManagers\SubjectsRelationManager;
use App\Models\Diploma;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DiplomaResource extends Resource
{
    protected static ?string $model = Diploma::class;
    protected static ?string $pluralModelLabel = 'الدبلومات';
    protected static ?string $navigationLabel = 'الدبلومات';
    protected static ?string $modelLabel = 'الدبلوم';

    // protected static ?string $recordTitleAttribute = 'دبلوم';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(Diploma::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dip_name')
                    ->label('اسم الدبلوم')
                    ->searchable(),
                TextColumn::make('dip_price')
                    ->label('سعر الدبلوم')
                    ->searchable()
                    ->numeric($decimalPlace = 2),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info')
                    ->pluralModelLabel('عرض الدبلوم')
                    ->modalHeading('Member Info'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(fn($record): string => 'حذف الدبلوم: ' . $record->cat_name),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('لاتوجد دبلومات')
            ->emptyStateDescription('قم بإضافة الدبلومات');
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('معلومات الدبلوم')
                    ->schema(
                        [
                            TextEntry::make('dip_name')
                                ->label('اسم الدبلوم'),
                            TextEntry::make('dip_price')
                                ->label('سعر الدبلوم')
                                ->numeric($decimalPlace = 2),
                        ]
                    )->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubjectsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiplomas::route('/'),
            'view' => Pages\ViewDiploma::route('/{record}'),
            // 'create' => Pages\CreateDiploma::route('/create'),
            // 'edit' => Pages\EditDiploma::route('/{record}/edit'),
        ];
    }
}
