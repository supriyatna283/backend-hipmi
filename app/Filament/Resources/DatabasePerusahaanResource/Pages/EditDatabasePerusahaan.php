<?php

namespace App\Filament\Resources\DatabasePerusahaanResource\Pages;

use App\Filament\Resources\DatabasePerusahaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatabasePerusahaan extends EditRecord
{
    protected static string $resource = DatabasePerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
