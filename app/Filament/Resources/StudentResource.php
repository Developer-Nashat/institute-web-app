<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationGroup = 'المدخلات الإدارية';

    protected static ?string $pluralModelLabel = 'الطلاب';

    protected static ?string $navigationLabel = 'الطلاب';

    protected static ?string $modelLabel = 'الطالب';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Student::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ar_name')
                    ->label('الاسم بالعربي')
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->label('تاريخ الميلاد'),
                Tables\Columns\TextColumn::make('student_gender')
                    ->label('النوع')
                    ->getStateUsing(function ($record) {
                        return $record->student_gender === 'M' ? 'ذكر' : 'أنثى';
                    }),
                Tables\Columns\TextColumn::make('first_phone')
                    ->label('رقم الهاتف الأول'),
                Tables\Columns\TextColumn::make('nationality.name')
                    ->label('الجنسية'),
                Tables\Columns\TextColumn::make('student_id_no')
                    ->label('رقم الطالب'),
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
                                    TextEntry::make('student_gender')
                                        ->label('النوع')
                                        ->getStateUsing(function ($record) {
                                            return $record->student_gender === 'M' ? 'ذكر' : 'أنثى';
                                        }),
                                    TextEntry::make('nationality.name')
                                        ->label('الجنسية'),
                                    TextEntry::make('date_of_birth')
                                        ->label('تاريخ الميلاد'),
                                    ImageEntry::make('student_img')
                                        ->label('صورة الطالب')
                                ])->columnSpan(2)
                                ->columns(3),

                            Tab::make('بيانات أخرى')
                                ->schema([

                                    TextEntry::make('student_id_no')
                                        ->label('رقم البطاقة'),
                                    TextEntry::make('student_email')
                                        ->label('البريد الألكتروني'),
                                    TextEntry::make('first_phone')
                                        ->label('رقم الهاتف الأول'),
                                    TextEntry::make('second_phone')
                                        ->label('رقم الهاتف الثاني'),
                                    TextEntry::make('student_address')
                                        ->label('العنوان')
                                        ->columnSpanFull(),
                                ])->columns(4)
                        ])->columnSpanFull()
                ]
            );
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}/view'),
        ];
    }
}
