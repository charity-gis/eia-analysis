<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;

class OpenLayer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.open-layer';

    public function getMaxContentWidth(): MaxWidth|string|null
    {
        return '7xl';
    }

//  protected static string $layout = 'layouts.app';

}
