<?php

namespace App\Filament\Resources\StaffResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecializationsRelationManager extends RelationManager
{
    protected static string $relationship = 'specializations';
    protected static ?string $modelLabel = 'التخصص';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('specialization_name')
                    ->label('اسم التخصص')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('specialization_name')
            ->columns([
                Tables\Columns\TextColumn::make('specialization_name')
                    ->label('اسم التخصص'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة تخصص')
                    ->modalWidth('md'),
                Tables\Actions\AttachAction::make()
                    ->color('info')
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                Tables\Actions\EditAction::make()
                    ->modalWidth('md')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->heading('تخصصات الموظف')
            ->emptyStateHeading('لاتوجد تخصصات لهذا الموظف')
            ->emptyStateDescription('قم بإضافة تخصصات له');
    }
}
