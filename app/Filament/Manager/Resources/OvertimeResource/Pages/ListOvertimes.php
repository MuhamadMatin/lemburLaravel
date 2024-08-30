<?php

namespace App\Filament\Manager\Resources\OvertimeResource\Pages;

use Filament\Actions;
use App\Exports\OvertimeExport;
use App\Imports\OvertimeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Manager\Resources\OvertimeResource;

class ListOvertimes extends ListRecords
{
    protected static string $resource = OvertimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('Export Excel')
                ->action('export')
                ->color('success'),
            Actions\Action::make('Import Excel')
                ->action('export')
                ->color('danger')
                ->form([
                    FileUpload::make('file')
                        ->label('Upload File Excel')
                        ->disk('public'),
                ])
                ->action(function (array $data): void {
                    try {
                        Excel::import(
                            new OvertimeImport,
                            Storage::disk('public')->path($data['file']),
                        );
                        Notification::make()
                            ->title('success')
                            ->body('Berhasil Import Data Excel')
                            ->success()
                            ->send();
                    } catch (\Throwable $th) {
                        Notification::make()
                            ->title('Gagal Import Data Excel')
                            ->body($th->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    public function export()
    {
        return Excel::download(new OvertimeExport, 'overtime.xlsx');
    }
}
