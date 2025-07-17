<?php

namespace App\Filament\Resources\CrowdfundingResource\Pages;

use App\Filament\Resources\CrowdfundingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCrowdfunding extends EditRecord
{
    protected static string $resource = CrowdfundingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
