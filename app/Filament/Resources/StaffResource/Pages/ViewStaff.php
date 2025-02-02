<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewStaff extends ViewRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('return_to_staff')
                ->label('تراجع')
                ->url(fn(): string => StaffResource::getUrl('index'))
                ->icon('heroicon-o-chevron-right')
                ->color('gray')
                ->link()


        ];
    }
}
