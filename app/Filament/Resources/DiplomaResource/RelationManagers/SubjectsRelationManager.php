<?php

namespace App\Filament\Resources\DiplomaResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';
    protected static ?string $modelLabel = 'المواد';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    TextInput::make('order')
                        ->label('ترتيب المادة')
                        ->required()
                        ->columnSpanFull()
                ]
            )
            ->columns(['lg' => 2]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sub_name')
            ->columns([
                Tables\Columns\TextColumn::make('sub_name')
                    ->label('اسم المادة'),
                Tables\Columns\TextColumn::make('order')
                    ->label('ترتيب المادة'),
            ])->searchable(false)
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->color('info')
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('order')
                            ->label('ترتيب المادة')->required(),
                    ])
                    ->preloadRecordSelect()
                // ->slideOver(),

            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                Tables\Actions\EditAction::make()
                    ->modalWidth('md')
            ])
            ->heading('مواد الدبلوم')
            ->emptyStateHeading('لاتوجد مواد لهذا الدبلوم')
            ->emptyStateDescription('قم بإضافة المواد له')

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
