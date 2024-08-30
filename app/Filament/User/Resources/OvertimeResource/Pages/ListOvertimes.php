<?php

namespace App\Filament\User\Resources\OvertimeResource\Pages;

use App\Filament\User\Resources\OvertimeResource;
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
