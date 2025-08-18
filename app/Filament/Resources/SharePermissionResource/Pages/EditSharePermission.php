<?php

namespace App\Filament\Resources\SharePermissionResource\Pages;

use App\Filament\Resources\SharePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSharePermission extends EditRecord
{
    protected static string $resource = SharePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
