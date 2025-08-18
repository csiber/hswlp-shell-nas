<?php

namespace App\Filament\Resources\ShareResource\Pages;

use App\Filament\Resources\ShareResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShare extends EditRecord
{
    protected static string $resource = ShareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
