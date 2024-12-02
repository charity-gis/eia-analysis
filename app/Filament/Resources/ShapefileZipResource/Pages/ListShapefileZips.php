<?php

namespace App\Filament\Resources\ShapefileZipResource\Pages;

use App\Filament\Resources\ShapefileZipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShapefileZips extends ListRecords
{
    protected static string $resource = ShapefileZipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
