<?php

namespace App\Filament\Resources\PetaBisnisResource\Pages;

use App\Filament\Resources\PetaBisnisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPetaBisnis extends ListRecords
{
    protected static string $resource = PetaBisnisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
