<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()->limit(40)
                    ->extraAttributes(['class' => 'font-semibold'])
                    ->description(
                        fn($record) =>
                        $record->serial_number ?: '-'
                    ),
                TextColumn::make('slug')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn($record) => $record->slug)
                    ->extraAttributes(['class' => 'text-gray-500 text-sm']),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->extraAttributes(['class' => 'font-semibold text-gray-800']),

                ImageColumn::make('image')
                    ->label('Gambar')
                    ->square()
                    ->imageWidth(90)
                    ->imageHeight(45)
                    ->getStateUsing(function ($record) {
                        if ($record->image) {
                            return asset("storage/{$record->image}");
                        }
                        return asset('assets/img/no-image.webp');
                    })
                    ->extraImgAttributes(['title' => 'Articles Image', 'loading' => 'lazy', 'style' => 'border-radius: 0.375rem; object-fit: cover;']),

                TextColumn::make('cost_price')
                    ->label('Harga Modal')
                    ->money('idr', true)
                    ->sortable()
                    ->extraAttributes(['class' => 'font-bold text-gray-900']),

                TextColumn::make('price')
                    ->label('Harga Jual')
                    ->money('idr', true)
                    ->sortable()
                    ->extraAttributes(['class' => 'font-bold text-gray-900']),

                TextColumn::make('discount_price')
                    ->label('Harga Promo')
                    ->placeholder('no discount price')
                    ->money('idr', true)
                    ->sortable()
                    ->extraAttributes(['class' => 'text-red-600 font-bold'])
                    ->toggleable(),

                TextColumn::make('stock')
                    ->label('stok')
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state <= 5 => 'danger',
                        $state <= 20 => 'warning',
                        default => 'success',
                    })
                    ->sortable()
                    ->numeric(),

                TextColumn::make('shopee_sync_status')
                    ->label('Shopee Sync')
                    ->badge()
                    ->placeholder('belum aktif')
                    ->color(fn(?string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'pending' => 'warning',
                        'restored' => 'info',
                        default => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('shopee_last_synced_at')
                    ->label('Sync Terakhir')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('link_shopee')
                    ->label('Shopee')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => $state ? 'Link' : null)
                    ->url(fn($state) => $state)
                    ->openUrlInNewTab()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('link_tokopedia')
                    ->label('Tokopedia')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => $state ? 'Link' : null)
                    ->url(fn($state) => $state)
                    ->openUrlInNewTab()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable()
                    ->colors([
                        'success' => fn($state) => $state,
                        'danger' => fn($state) => !$state,
                    ]),

                TextColumn::make('shopee_publish_status')
                    ->label('Publish Shopee')
                    ->badge()
                    ->placeholder('belum publish')
                    ->color(fn(?string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_active')
                    ->options([
                        '0' => 'Draft',
                        '1' => 'Published',
                    ]),
                SelectFilter::make('category.name')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->placeholder('Select Category')
            ])
            ->recordActions([
                \Filament\Actions\Action::make('record_sale')
                    ->label('Catat Terjual')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah Terjual')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('negotiated_price')
                            ->label('Harga Terjual (Nego)')
                            ->helperText('Harga kesepakatan dengan customer. Default adalah harga produk.')
                            ->default(fn($record) => $record->price)
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('customer_info')
                            ->label('Info Customer')
                            ->placeholder('Nama Customer / No. WA')
                            ->helperText('Masukkan Nama atau Nomor Telepon customer.')
                            ->default('Umum')
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('transaction_date')
                            ->label('Tanggal Transaksi')
                            ->native(false)
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (\App\Models\Product $record, array $data) {
                        // 1. Cek stok
                        if ($record->stock < $data['quantity']) {
                            \Filament\Notifications\Notification::make()
                                ->title('Stok tidak cukup!')
                                ->danger()
                                ->send();

                            return;
                        }

                        $cost = $record->cost_price;
                        $sellingPrice = $record->price;
                        $negotiatedPrice = $data['negotiated_price'];

                        $profit = ($negotiatedPrice - $cost) * $data['quantity'];

                        \App\Models\Sale::create([
                            'product_id' => $record->id,
                            'quantity' => $data['quantity'],
                            'cost_price' => $cost,
                            'selling_price' => $sellingPrice,
                            'negotiated_price' => $negotiatedPrice,
                            'total_profit' => $profit,
                            'customer_info' => $data['customer_info'],
                            'transaction_date' => $data['transaction_date'],
                        ]);

                        // 4. Kurangi stok
                        $record->decrement('stock', $data['quantity']);
                        $record->refresh();

                        if ($record->canSyncShopeeStock()) {
                            \App\Jobs\SyncProductStockToShopeeJob::dispatch($record->id);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Penjualan berhasil dicatat')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('publish_to_shopee')
                    ->label('Publish to Shopee')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn(\App\Models\Product $record) => !$record->isPublishedToShopee())
                    ->action(function (\App\Models\Product $record) {
                        if (!$record->canPublishToShopee()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Data Shopee belum lengkap.')
                                ->body('Lengkapi toko, kategori Shopee, brand, logistik, berat, dimensi, gambar, nama, deskripsi, harga, dan stok sebelum publish.')
                                ->danger()
                                ->send();

                            return;
                        }

                        \App\Jobs\PublishProductToShopeeJob::dispatch($record->id);

                        \Filament\Notifications\Notification::make()
                            ->title('Produk dikirim ke antrean publish Shopee.')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('sync_shopee_stock')
                    ->label('Sync Stok Shopee')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (\App\Models\Product $record) {
                        if (!$record->canSyncShopeeStock()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Produk belum dikonfigurasi untuk Shopee.')
                                ->danger()
                                ->send();

                            return;
                        }

                        \App\Jobs\SyncProductStockToShopeeJob::dispatch($record->id);

                        \Filament\Notifications\Notification::make()
                            ->title('Sinkronisasi stok dikirim ke queue.')
                            ->success()
                            ->send();
                    }),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
