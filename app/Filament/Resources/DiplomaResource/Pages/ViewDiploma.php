<?php

namespace App\Filament\Resources\DiplomaResource\Pages;

use App\Filament\Resources\DiplomaResource;
use App\Models\Diploma;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDiploma extends ViewRecord
{
    protected static string $resource = DiplomaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->slideOver()
                ->form(Diploma::getForm()),

            Action::make('return_to_Diploma')
                ->label('تراجع')
                ->url(fn(): string => DiplomaResource::getUrl('index'))
                ->icon('heroicon-o-chevron-right')
                ->color('gray')
                ->link()
        ];
    }
}
