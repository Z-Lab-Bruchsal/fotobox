<?php

namespace App\Filament\Resources\Photoprofiles\Pages;

use App\Filament\Resources\Photoprofiles\PhotoprofileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhotoprofiles extends ListRecords
{
    protected static string $resource = PhotoprofileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
