<?php

namespace App\Filament\Resources\Photoprofiles;

use App\Filament\Resources\Photoprofiles\Pages\CreatePhotoprofile;
use App\Filament\Resources\Photoprofiles\Pages\EditPhotoprofile;
use App\Filament\Resources\Photoprofiles\Pages\ListPhotoprofiles;
use App\Filament\Resources\Photoprofiles\Schemas\PhotoprofileForm;
use App\Filament\Resources\Photoprofiles\Tables\PhotoprofilesTable;
use App\Models\Photoprofile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PhotoprofileResource extends Resource
{
    protected static ?string $model = Photoprofile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PhotoprofileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhotoprofilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPhotoprofiles::route('/'),
            'create' => CreatePhotoprofile::route('/create'),
            'edit' => EditPhotoprofile::route('/{record}/edit'),
        ];
    }
}
