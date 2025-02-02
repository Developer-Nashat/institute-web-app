<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'people_id' => 'integer',
    ];

    public static function getForm()
    {
        return [
            Tabs::make('Tabs')
                ->tabs([

                    Tab::make('البيانات الأساسية')
                        ->schema([
                            TextInput::make('ar_name')
                                ->label('الاسم بالعربي')
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'يوجد طالب بهذا الأسم',
                                    'required' => 'حقل الاسم بالعربي مطلوب',
                                ])->required()
                                ->maxLength(255),
                            TextInput::make('en_name')
                                ->label('الاسم بالإنجليزي')
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'يوجد طالب بهذا الأسم'
                                ])->maxLength(255),
                            Select::make('student_gender')
                                ->label('النوع')
                                ->options([
                                    'M' => 'ذكر',
                                    'F' => 'أنثى'
                                ])
                                ->default('M'),
                            Select::make('nationality_id')
                                ->label('الجنسية')
                                ->relationship('nationality', 'name')
                                ->required()
                                ->validationMessages([
                                    'required' => 'يجب ان تختر الجنسية',
                                ])
                                ->searchable()
                                ->preload()
                                ->createOptionForm(Nationality::getform())
                                ->editOptionForm(Nationality::getForm()),
                            DatePicker::make('date_of_birth')
                                ->label('تاريخ الميلاد'),
                            FileUpload::make('student_img')
                                ->label('صورة الطالب')
                                ->disk('public')
                                ->directory('std_img')
                                ->image()
                                ->imageEditor()
                        ])->columns(3),
                    Tab::make('بيانات أخرى')->schema([
                        TextInput::make('student_id_no')
                            ->label('رقم البطاقة'),
                        TextInput::make('student_email')
                            ->label('البريد الألكتروني')
                            ->email()
                            ->unique()
                            ->validationMessages([
                                'unique' => 'هذا البريد الألكتروني مستخدم'
                            ])
                            ->maxLength(255),
                        TextInput::make('first_phone')
                            ->label('رقم الهاتف الأول')
                            ->tel()
                            ->maxLength(15),
                        TextInput::make('second_phone')
                            ->label('رقم الهاتف الثاني')
                            ->tel()
                            ->maxLength(15),
                        Textarea::make('student_address')
                            ->label('العنوان')
                            ->columnSpanFull(),

                    ])->columns(4)
                ])->columnSpanFull()
        ];
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }
}
