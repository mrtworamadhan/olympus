<?php

namespace App\Filament\App\Resources\Categories\Pages;

use App\Filament\App\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class DrawingCategory extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CategoryResource::class;

    protected string $view = 'filament.app.resources.categories.pages.drawing-category';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
