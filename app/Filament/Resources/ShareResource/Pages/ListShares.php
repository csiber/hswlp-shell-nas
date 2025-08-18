<?php

namespace App\Filament\Resources\ShareResource\Pages;

use App\Filament\Resources\ShareResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShares extends ListRecords
{
    protected static string $resource = ShareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
