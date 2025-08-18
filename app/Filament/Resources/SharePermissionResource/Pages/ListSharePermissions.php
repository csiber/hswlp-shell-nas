<?php

namespace App\Filament\Resources\SharePermissionResource\Pages;

use App\Filament\Resources\SharePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSharePermissions extends ListRecords
{
    protected static string $resource = SharePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
