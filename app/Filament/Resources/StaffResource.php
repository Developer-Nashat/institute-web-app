<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaffResource\Pages;
use App\Filament\Resources\StaffResource\RelationManagers;
use App\Filament\Resources\StaffResource\RelationManagers\SpecializationRelationManager;
use App\Filament\Resources\StaffResource\RelationManagers\SpecializationsRelationManager;
use App\Models\Staff;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static ?string $navigationGroup = 'المدخلات الإدارية';

    protected static ?string $pluralModelLabel = 'الموظفين';

    protected static ?string $navigationLabel = 'الموظفين';

    protected static ?string $modelLabel = 'الموظف';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Staff::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ar_name')
                    ->label('الاسم بالعربي')
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('gender')
                    ->label('النوع')
                    ->getStateUsing(function ($record) {
                        return $record->gender === 'M' ? 'ذكر' : 'أنثى';
                    }),
                Tables\Columns\TextColumn::make('first_phone_number')
                    ->label('رقم الهاتف الأول'),
                Tables\Columns\TextColumn::make('nationality.name')
                    ->label('الجنسية'),
                Tables\Columns\TextColumn::make('position.name')
                    ->label('الوظيفة'),
                Tables\Columns\TextColumn::make('is_teacher')
                    ->label('نوع الموظف')
                    ->getStateUsing(function ($record) {
                        return $record->is_teacher === true  ? 'مدرب' : 'موظف إداري';
                    })->badge(function ($record) {
                        return $record->is_teacher === true  ? 'primary' : '';
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->tooltip('الإجرائات')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema(
                [
                    Tabs::make()
                        ->tabs([
                            Tab::make('البيانات الأساسية')

                                ->schema([
                                    TextEntry::make('ar_name')
                                        ->label('الاسم بالعربي'),
                                    TextEntry::make('en_name')
                                        ->label('الاسم بالإنجليزي'),
                                    TextEntry::make('gender')
                                        ->label('النوع')
                                        ->getStateUsing(function ($record) {
                                            return $record->gender === 'M' ? 'ذكر' : 'أنثى';
                                        }),
                                    TextEntry::make('position.position_name')
                                        ->label('الوظيفة'),
                                    TextEntry::make('nationality.name')
                                        ->label('الجنسية'),
                                    TextEntry::make('is_teacher')
                                        ->label('نوع الموظف')
                                        ->getStateUsing(function ($record) {
                                            return $record->is_teacher ? 'مدرب' : 'موظف إداري';
                                        })->badge()
                                        ->color(function ($state) {
                                            if ($state === 'is_teacher') {
                                                return 'success';
                                            }
                                            return 'primary';
                                        }),
                                    TextEntry::make('date_of_birth')
                                        ->label('تاريخ الميلاد'),
                                    TextEntry::make('hire_date')
                                        ->label('تاريخ التوظيف'),
                                ])->columnSpan(2)
                                ->columns(3),

                            Tab::make('بيانات أخرى')
                                ->schema([

                                    TextEntry::make('salary')
                                        ->label('الراتب')
                                        ->numeric(),
                                    TextEntry::make('percentage')
                                        ->label('النسبة')
                                        ->numeric(),


                                    TextEntry::make('staff_id_no')
                                        ->label('رقم البطاقة'),
                                    TextEntry::make('email')
                                        ->label('البريد الألكتروني'),
                                    TextEntry::make('first_phone_number')
                                        ->label('رقم الهاتف الأول'),
                                    TextEntry::make('second_phone_number')
                                        ->label('رقم الهاتف الثاني'),
                                    TextEntry::make('address')
                                        ->label('العنوان')
                                        ->columnSpanFull(),
                                ])->columns(3)
                        ])->columnSpanFull()
                ]
            );
    }

    public static function getRelations(): array
    {
        return [
            SpecializationsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStaff::route('/'),
            'create' => Pages\CreateStaff::route('/create'),
            'edit' => Pages\EditStaff::route('/{record}/edit'),
            'view' => Pages\ViewStaff::route('/{record}/view'),
        ];
    }
}
