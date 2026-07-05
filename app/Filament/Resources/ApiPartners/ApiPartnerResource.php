<?php

namespace App\Filament\Resources\ApiPartners;

use App\Filament\Resources\ApiPartners\Pages\CreateApiPartner;
use App\Filament\Resources\ApiPartners\Pages\EditApiPartner;
use App\Filament\Resources\ApiPartners\Pages\ListApiPartners;
use App\Filament\Resources\ApiPartners\Schemas\ApiPartnerForm;
use App\Filament\Resources\ApiPartners\Tables\ApiPartnersTable;
use App\Models\ApiPartner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApiPartnerResource extends Resource
{
    protected static ?string $model = ApiPartner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static string|\UnitEnum|null $navigationGroup = 'API';

    protected static ?string $navigationLabel = 'Partner Websites';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ApiPartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiPartnersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListApiPartners::route('/'),
            'create' => CreateApiPartner::route('/create'),
            'edit'   => EditApiPartner::route('/{record}/edit'),
        ];
    }
}
