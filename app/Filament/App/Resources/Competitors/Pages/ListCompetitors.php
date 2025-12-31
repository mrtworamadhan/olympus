<?php

namespace App\Filament\App\Resources\Competitors\Pages;

use App\Filament\App\Resources\Competitors\CompetitorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListCompetitors extends ListRecords
{
    protected static string $resource = CompetitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Tim'),

            'pending' => Tab::make('Pending')
                ->badge(CompetitorResource::getModel()::where('status', 'pending')
                    ->count())
                ->badgeColor('danger') 
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->where('status', 'pending')),

            'submitted' => Tab::make('Perlu Review')
                ->badge(CompetitorResource::getModel()::where('status', 'submitted')
                    ->count())
                ->badgeColor('info') 
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->where('status', 'submitted')),

            'approved' => Tab::make('Terdaftar')
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->where('status', 'approved')),

            'rejected' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn(Builder $query) => $query
                    ->where('status', 'rejected')),
        ];
    }
}
