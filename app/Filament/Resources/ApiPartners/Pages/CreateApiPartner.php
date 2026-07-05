<?php

namespace App\Filament\Resources\ApiPartners\Pages;

use App\Filament\Resources\ApiPartners\ApiPartnerResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateApiPartner extends CreateRecord
{
    protected static string $resource = ApiPartnerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-generate a unique 64-char API key
        $data['api_key'] = Str::random(64);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
