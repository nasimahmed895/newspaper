<?php

namespace App\Filament\Resources\ApiPartners\Pages;

use App\Filament\Resources\ApiPartners\ApiPartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApiPartner extends EditRecord
{
    protected static string $resource = ApiPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
