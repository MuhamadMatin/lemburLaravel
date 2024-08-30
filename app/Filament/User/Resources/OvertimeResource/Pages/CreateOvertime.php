<?php

namespace App\Filament\User\Resources\OvertimeResource\Pages;

use App\Filament\User\Resources\OvertimeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOvertime extends CreateRecord
{
    protected static string $resource = OvertimeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
