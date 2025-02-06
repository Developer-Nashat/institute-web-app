<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliationResource\Pages;
use App\Filament\Resources\AffiliationResource\RelationManagers;
use App\Models\Affiliation;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AffiliationResource extends Resource
{
    protected static ?string $model = Affiliation::class;

    protected static ?string $navigationGroup = 'المدخلات الإدارية';

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string  $modelLabel = 'الجهة';

    protected static ?string  $pluralModelLabel = 'الجهات';

    protected static ?string  $navigationLabel = 'الجهات';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('اسم الجهة')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'توجد جهة بهذا الاسم'
                    ])
                    ->maxLength(255),
                Forms\Components\TextInput::make('supervisor')
                    ->required()
                    ->label('اسم المشرف')
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->label('العنوان')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('first_phone')
                    ->tel()
                    ->label('رقم الهاتف الأول')
                    ->maxLength(15),
                Forms\Components\TextInput::make('second_phone')
                    ->tel()
                    ->label('رقم الهاتف الثاني')
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('البريد الألكتروني')
                    ->unique()
                    ->validationMessages(
                        [
                            'unique' => 'البريد الالكتروني هذا موجد لدى جهة اخرى'
                        ]
                    )
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الجهة')
                    ->weight(FontWeight::Bold)
                    ->wrap(),
                Tables\Columns\TextColumn::make('supervisor')
                    ->label('اسم المشرف')
                    ->weight(FontWeight::SemiBold)
                    ->wrap(),
                Tables\Columns\TextColumn::make('address')
                    ->label('العنوان')
                    ->toggleable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('first_phone')
                    ->label('رقم الهاتف الأول')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('second_phone')
                    ->label('رقم الهاتف الثاني')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الألكتروني')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary')
                        ->slideOver(),
                    Tables\Actions\DeleteAction::make()
                ])->tooltip('الإجرائات')
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
            'index' => Pages\ListAffiliations::route('/'),
            // 'create' => Pages\CreateAffiliation::route('/create'),
            // 'edit' => Pages\EditAffiliation::route('/{record}/edit'),
        ];
    }
}
