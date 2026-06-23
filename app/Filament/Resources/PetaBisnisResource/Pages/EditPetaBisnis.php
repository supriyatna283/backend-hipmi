<?php

namespace App\Filament\Resources\PetaBisnisResource\Pages;

use App\Filament\Resources\PetaBisnisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPetaBisnis extends EditRecord
{
    protected static string $resource = PetaBisnisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
