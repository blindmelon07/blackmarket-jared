<?php

namespace App\Filament\Admin\Resources\SellerApplicationResource\Pages;

use App\Filament\Admin\Resources\SellerApplicationResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditSellerApplication extends EditRecord
{
    protected static string $resource = SellerApplicationResource::class;

 protected function afterSave(): void
{
    $record = $this->record;

    // Debug: dump status to log
    Log::info('Status after save:', ['status' => $record->status]);

    if ($record->status === 'approved') {
        $user = $record->user;
        Log::info('User found', ['user_id' => $user?->id]);
        if ($user && !$user->hasRole('seller')) {
            $user->assignRole('seller');
            Log::info('Seller role assigned');
        }
    }
}
}
