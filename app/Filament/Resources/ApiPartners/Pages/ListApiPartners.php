<?php

namespace App\Filament\Resources\ApiPartners\Pages;

use App\Filament\Resources\ApiPartners\ApiPartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiPartners extends ListRecords
{
    protected static string $resource = ApiPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
