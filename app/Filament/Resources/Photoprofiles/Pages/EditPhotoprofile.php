<?php

namespace App\Filament\Resources\Photoprofiles\Pages;

use App\Filament\Resources\Photoprofiles\PhotoprofileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPhotoprofile extends EditRecord
{
    protected static string $resource = PhotoprofileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
