<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Jobs\SyncProductStockToShopeeJob;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected ?int $oldStock = null;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->oldStock = (int) $this->record->stock;

        return $data;
    }

    protected function afterSave(): void
    {
        $newStock = (int) $this->record->refresh()->stock;

        if ($this->oldStock !== $newStock && $this->record->canSyncShopeeStock()) {
            SyncProductStockToShopeeJob::dispatch($this->record->id);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
