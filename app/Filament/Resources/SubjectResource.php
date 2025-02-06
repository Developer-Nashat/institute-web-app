<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\RelationManagers;
use App\Models\Subject;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationGroup = 'المدخلات الإدارية';

    protected static ?string $pluralModelLabel = 'المواد';

    protected static ?string $navigationLabel = 'المواد';

    protected static ?string $modelLabel = 'المادة';

    protected static ?int $navigationSort = 1;

    // protected static ?string $title = 'Custom Page Title';

    // protected ?string $heading = 'Custom Page Heading';

    // protected ?string $subheading = 'اسماء المواد';

    // protected static ?string $navigationLabel = 'Custom Navigation Label';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Subject::getForm())->columns(['lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sub_name')
                    ->label('اسم المادة')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('sub_price')
                    ->label('سعر المادة')
                    ->searchable(isIndividual: true, isGlobal: false)
                    ->numeric($decimalPlace = 2),
                TextColumn::make('category.cat_name')
                    ->label('التصنيف')
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('total_days')
                    ->label('عدد الأيام')
                    ->searchable(false)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_hours')
                    ->label('عدد الساعات')
                    ->searchable(false)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            // ->toggleColumnsTriggerAction(
            //     fn(Action $action) => $action
            //         ->button()
            //         ->label('Toggle columns')
            // )
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(fn($record): string => 'حذف المادة: ' . $record->sub_name)


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    // ->label('حذف ماتم اختياره'),
                ]),
            ])
            ->emptyStateHeading('لاتوجد مواد')
            ->emptyStateDescription('قم بإضافة المواد');
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
            'index' => Pages\ListSubjects::route('/'),
            // 'create' => Pages\CreateSubject::route('/create'),
            // 'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
