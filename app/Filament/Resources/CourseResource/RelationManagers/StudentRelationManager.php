<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Filament\Resources\StudentResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $modelLabel = 'الطالب';

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ar_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ar_name')
            ->columns([
                Tables\Columns\TextColumn::make('ar_name')
                    ->label('اسم الطلاب'),
                Tables\Columns\TextColumn::make('student_gender')
                    ->label('النوع')
                    ->getStateUsing(function ($record) {
                        return $record->student_gender === 'M' ? 'ذكر' : 'أنثى';
                    }),
                Tables\Columns\TextColumn::make('first_phone')
                    ->label('رقم الهاتف'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('إضافة طالب')
                    ->color('info')
                    ->preloadRecordSelect()
            ])
            ->heading('الطلاب')
            ->emptyStateHeading('لايوجد طلاب في هذه الدورة')
            ->emptyStateDescription('قم بإضافة الطلاب للدورة')

            ->actions([
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
