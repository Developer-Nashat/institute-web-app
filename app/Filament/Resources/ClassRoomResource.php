<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassRoomResource\Pages;
use App\Filament\Resources\ClassRoomResource\RelationManagers;
use App\Models\ClassRoom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    protected static ?string $navigationGroup = 'المدخلات الإدارية';

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string  $modelLabel = 'قاعة التدريب';

    protected static ?string  $pluralModelLabel = 'قاعات التدريب';

    protected static ?string  $navigationLabel = 'قاعات التدريب';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم القاعة')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'توجد قاعدة تدريب بهذا الاسم'
                    ])
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('capacity')
                    ->required()
                    ->label('عدد مقاعد')
                    ->numeric(),
                Forms\Components\TextInput::make('room_no')
                    ->label('رقم القاعة')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم القاعة'),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('عدد مقاعد')
                    ->numeric(),
                Tables\Columns\TextColumn::make('room_no')
                    ->label('رقم القاعة')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListClassRooms::route('/'),
            // 'create' => Pages\CreateClassRoom::route('/create'),
            // 'edit' => Pages\EditClassRoom::route('/{record}/edit'),
        ];
    }
}
