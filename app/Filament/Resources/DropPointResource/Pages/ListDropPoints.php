<?php

namespace App\Filament\Resources\DropPointResource\Pages;

use App\Filament\Resources\DropPointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDropPoints extends ListRecords
{
    protected static string $resource = DropPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
