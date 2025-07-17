<?php

namespace App\Filament\Resources\DropPointResource\Pages;

use App\Filament\Resources\DropPointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDropPoint extends EditRecord
{
    protected static string $resource = DropPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
