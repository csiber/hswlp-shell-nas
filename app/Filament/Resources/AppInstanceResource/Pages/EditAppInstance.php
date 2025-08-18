<?php

namespace App\Filament\Resources\AppInstanceResource\Pages;

use App\Filament\Resources\AppInstanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppInstance extends EditRecord
{
    protected static string $resource = AppInstanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
