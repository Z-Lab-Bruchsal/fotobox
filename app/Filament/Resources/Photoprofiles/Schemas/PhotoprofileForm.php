<?php

namespace App\Filament\Resources\Photoprofiles\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PhotoprofileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Checkbox::make('active')
                    ->label('Aktiv'),
                Textarea::make('commands')
                    ->label('Befehle')
                    ->required(),
            ]);
    }
}
