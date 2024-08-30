<?php

namespace App\Filament\Manager\Resources\OvertimeResource\Pages;

use App\Filament\Manager\Resources\OvertimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOvertimes extends ListRecords
{
    protected static string $resource = OvertimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
