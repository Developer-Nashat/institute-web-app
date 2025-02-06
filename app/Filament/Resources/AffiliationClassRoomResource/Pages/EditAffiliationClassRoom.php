<?php

namespace App\Filament\Resources\AffiliationClassRoomResource\Pages;

use App\Filament\Resources\AffiliationClassRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAffiliationClassRoom extends EditRecord
{
    protected static string $resource = AffiliationClassRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
