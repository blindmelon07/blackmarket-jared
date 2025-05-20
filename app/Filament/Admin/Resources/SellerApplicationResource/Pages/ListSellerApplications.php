<?php

namespace App\Filament\Admin\Resources\SellerApplicationResource\Pages;

use App\Filament\Admin\Resources\SellerApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellerApplications extends ListRecords
{
    protected static string $resource = SellerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
