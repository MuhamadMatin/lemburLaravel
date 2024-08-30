<?php

namespace App\Filament\Admin\Resources\OvertimeResource\Pages;

use App\Filament\Admin\Resources\OvertimeResource;
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
