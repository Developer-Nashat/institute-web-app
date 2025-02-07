<?php

namespace App\Models;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Wallo\FilamentSelectify\Components\ToggleButton;

class Staff extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'staff_salary' => 'decimal',
        'staff_percentage' => 'decimal',
        'position_id' => 'integer',
        'is_teacher' => 'boolean',
    ];

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

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
                                    'unique' => 'يوجد موظف بهذا الأسم',
                                    'required' => 'حقل الاسم بالعربي مطلوب',
                                ])->required()
                                ->maxLength(255),
                            TextInput::make('en_name')
                                ->label('الاسم بالإنجليزي')
                                ->unique(ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'يوجد موظف بهذا الأسم'
                                ])->maxLength(255),
                            Select::make('gender')
                                ->label('النوع')
                                ->options([
                                    'M' => 'ذكر',
                                    'F' => 'أنثى'
                                ])->native(false)
                                ->default('M'),
                            Select::make('nationality_id')
                                ->label('الجنسية')
                                ->relationship('nationality', 'name')
                                ->required()
                                ->validationMessages([
                                    'required' => 'يجب ان تختر الجنسية',
                                ])->native(false)
                                ->searchable()
                                ->preload()
                                ->createOptionForm(Nationality::getform())
                                ->editOptionForm(Nationality::getForm()),
                            Select::make('position_id')
                                ->relationship('position', 'position_name')
                                ->label('الوظيفة')
                                ->required()
                                ->native(false)
                                ->validationMessages([
                                    'required' => 'يجب ان تختر الوظيفة',
                                ])
                                ->searchable()
                                ->preload()
                                ->createOptionForm(Position::getform())
                                ->editOptionForm(Position::getForm()),
                            ToggleButtons::make('is_teacher')
                                ->label('نوع الموظف')
                                ->options([
                                    'true' => 'مدرب',
                                    'false' => 'موظف أداري',
                                ])
                                ->grouped()
                                ->default('true'),
                            DatePicker::make('date_of_birth')
                                ->label('تاريخ الميلاد'),
                            DatePicker::make('hire_date')
                                ->label('تاريخ التوظيف')
                        ])->columns(3),
                    Tab::make('بيانات أخرى')->schema([
                        TextInput::make('salary')
                            ->label('الراتب')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric(),
                        TextInput::make('percentage')
                            ->label('النسبة')
                            ->maxLength(4)
                            ->numeric(),

                        TextInput::make('staff_id_no')
                            ->label('رقم البطاقة'),
                        TextInput::make('email')
                            ->label('البريد الألكتروني')
                            ->email()
                            ->unique()
                            ->validationMessages([
                                'unique' => 'هذا البريد الألكتروني مستخدم'
                            ])
                            ->maxLength(255),
                        TextInput::make('first_phone_number')
                            ->label('رقم الهاتف الأول')
                            ->tel()
                            ->maxLength(15),
                        TextInput::make('second_phone_number')
                            ->label('رقم الهاتف الثاني')
                            ->tel()
                            ->maxLength(15),
                        Textarea::make('address')
                            ->label('العنوان')
                            ->columnSpanFull(),

                    ])->columns(3)
                ])->columnSpanFull()
        ];
    }
}
