<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'التصنيف';
    protected static ?string $pluralModelLabel = 'التصنيفات';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Category::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cat_name')
                    ->label("التصنيف"),
                TextColumn::make('subject_count')
                    ->label('عدد المواد')
                    ->counts('subjects')
                    ->searchable(false)

            ])
            ->searchPlaceholder('بحث')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading(fn($record): string => 'حذف التصنيف: ' . $record->cat_name)
                // ->modalDescription('تاكيد الحذف')
                // ->modalSubmitActionLabel('موافق')
                // ->modalCancelActionLabel('إلغاء')
                // ->successNotification(
                //     Notification::make()
                //         ->success()
                //         ->title('Employee Deleted')
                //         ->body('Employee has been Deleted Successfully.!')
                // ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    // ->modalHeading('حذف'),
                ]),
            ])

            ->emptyStateHeading('لاتوجد تصنيفات')
            ->emptyStateDescription('قم بإضافة التصنيفات');
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
            'index' => Pages\ListCategories::route('/'),
            // 'create' => Pages\CreateCategory::route('/create'),
            // 'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
