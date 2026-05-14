<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use App\Jobs\DeleteShopeeItemJob;
use App\Jobs\PublishProductToShopeeJob;
use App\Jobs\SyncProductStockToShopeeJob;
use App\Models\Product;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;
use Filament\Tables\Columns\ViewColumn;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->extraImgAttributes(['title' => 'Product Image', 'loading' => 'lazy', 'style' => 'border-radius: 0.375rem; object-fit: cover;']),
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()->limit(40)
                    ->extraAttributes(['class' => 'font-semibold'])
                    ->description(function (Product $record) {
                        $serial = $record->serial_number ?: '-';

                        if ($record->trashed()) {
                            return $serial . ' | Diarsipkan';
                        }

                        return $serial;
                    }),
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
                TextColumn::make('cost_price')
                    ->label('Harga Modal')
                    ->money('idr', true)
                    ->sortable()
                    ->extraAttributes(['class' => 'font-bold text-gray-900'])
                    ->toggleable(isToggledHiddenByDefault: true),

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
                ViewColumn::make('shopee_overview')
                    ->label('Shopee')
                    ->view('filament.tables.columns.shopee-overview')
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
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('shopee_last_synced_at')
                    ->label('Sync Terakhir')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('shopee_publish_status')
                    ->label('Publish Shopee')
                    ->badge()
                    ->placeholder('belum publish')
                    ->color(fn(?string $state): string => match ($state) {
                        'success' => 'success',
                        'failed' => 'danger',
                        'pending' => 'warning',
                        'deleted' => 'danger',
                        'not_found' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('shopee_item_status')
                    ->label('Status Item Shopee')
                    ->badge()
                    ->placeholder('-')
                    ->color(fn(?string $state): string => match ($state) {
                        'normal' => 'success',
                        'deleted' => 'danger',
                        'not_found' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->placeholder('Select Category'),
                TrashedFilter::make(),
            ])
            ->recordUrl(function (Product $record): ?string {
                if ($record->trashed()) {
                    return null;
                }

                return null;
            })
            ->recordActions([
                self::recordSaleAction(),

                ActionGroup::make([
                    self::publishToShopeeAction(),
                    self::syncShopeeStockAction(),
                    self::deleteFromShopeeAction(),
                    self::deleteFromShopeeAndInternalAction(),
                ])
                    ->label('Shopee')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('gray')
                    ->button(),

                ActionGroup::make([
                    ViewAction::make()->visible(fn(Product $record): bool => !$record->trashed()),
                    EditAction::make()->visible(fn(Product $record): bool => !$record->trashed()),

                    RestoreAction::make()
                        ->label('Pulihkan Produk')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('success')
                        ->visible(fn(Product $record): bool => $record->trashed())
                        ->after(function (Product $record) {
                            $record->forceFill([
                                'is_active' => false,
                            ])->saveQuietly();
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk berhasil dipulihkan.')
                                ->body('Produk dikembalikan sebagai draft. Periksa stok, harga, dan data Shopee sebelum dipublikasikan kembali.')
                        ),

                    DeleteAction::make()
                        ->label('Arsipkan')
                        ->icon('heroicon-o-archive-box')
                        ->modalHeading('Arsipkan produk?')
                        ->modalDescription('Produk akan disembunyikan dari daftar aktif, tetapi histori penjualan tetap tersimpan.')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk berhasil diarsipkan.')
                                ->body('Produk tidak tampil di katalog aktif, tetapi laporan penjualan tetap tersimpan.')
                        )
                        ->visible(fn(Product $record): bool => !$record->trashed())
                        ->before(function (Product $record) {
                            if (filled($record->shopee_item_id) && !in_array($record->shopee_item_status, ['deleted', 'not_found'], true)) {
                                throw ValidationException::withMessages([
                                    'product' => 'Produk ini masih terhubung ke Shopee. Gunakan aksi "Hapus dari Shopee" atau "Hapus Shopee + Internal" terlebih dahulu.',
                                ]);
                            }
                        }),

                    ForceDeleteAction::make()
                        ->label('Hapus Permanen')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->visible(fn(Product $record): bool => $record->trashed())
                        ->requiresConfirmation()
                        ->modalHeading('Hapus permanen produk?')
                        ->modalDescription('Menghapus produk ini akan menghapus seluruh data produk beserta riwayat penjualan terkait secara permanen. Tindakan ini tidak dapat dibatalkan.')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk berhasil dihapus permanen.')
                        ),
                ])
                    ->label('Kelola')
                    ->icon('heroicon-o-ellipsis-horizontal')
                    ->color('gray')
                    ->button(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Arsipkan Produk')
                        ->modalHeading('Arsipkan produk yang dipilih?')
                        ->modalDescription('Produk akan disembunyikan dari daftar aktif, tetapi histori penjualan tetap tersimpan.')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk berhasil diarsipkan.')
                                ->body('Produk yang dipilih sudah dipindahkan ke arsip.')
                        )
                        ->before(function ($records) {
                            $hasTrashedProduct = $records->contains(fn($record) => $record->trashed());

                            if ($hasTrashedProduct) {
                                throw ValidationException::withMessages([
                                    'products' => 'Beberapa produk yang dipilih sudah berada di arsip.',
                                ]);
                            }

                            $hasConnectedShopeeProduct = $records->contains(function ($record) {
                                return filled($record->shopee_item_id)
                                    && !in_array($record->shopee_item_status, ['deleted', 'not_found'], true);
                            });

                            if ($hasConnectedShopeeProduct) {
                                throw ValidationException::withMessages([
                                    'products' => 'Beberapa produk masih terhubung ke Shopee. Hapus dari Shopee terlebih dahulu atau gunakan aksi Hapus Shopee + Internal.',
                                ]);
                            }
                        }),

                    RestoreBulkAction::make()
                        ->label('Pulihkan Produk')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk berhasil dipulihkan.')
                                ->body('Produk yang dipilih dikembalikan ke daftar produk.')
                        ),

                    ForceDeleteBulkAction::make()
                        ->label('Hapus Permanen')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus permanen produk yang dipilih?')
                        ->modalDescription('Produk akan dihapus permanen dan semua data yang terkait dengan produk akan ikut terhapus.')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Produk berhasil dihapus permanen.')
                        )
                        ->before(function ($records) {
                            $hasActiveProduct = $records->contains(fn($record) => !$record->trashed());

                            if ($hasActiveProduct) {
                                throw ValidationException::withMessages([
                                    'products' => 'Hapus permanen hanya boleh dilakukan pada produk yang sudah diarsipkan.',
                                ]);
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    private static function recordSaleAction(): Action
    {
        return Action::make('record_sale')
            ->label('Catat Terjual')
            ->icon('heroicon-o-shopping-cart')
            ->color('success')
            ->button()
            ->visible(fn(Product $record): bool => !$record->trashed())
            ->form([
                TextInput::make('quantity')
                    ->label('Jumlah Terjual')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),

                TextInput::make('negotiated_price')
                    ->label('Harga Terjual')
                    ->helperText('Harga kesepakatan dengan customer. Default adalah harga produk.')
                    ->default(fn(Product $record) => $record->price)
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                TextInput::make('customer_info')
                    ->label('Info Customer')
                    ->placeholder('Nama Customer / No. WA')
                    ->default('Umum')
                    ->required(),

                DatePicker::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->native(false)
                    ->default(now())
                    ->required(),
            ])
            ->action(function (Product $record, array $data) {
                if ($record->trashed()) {
                    Notification::make()
                        ->title('Produk sudah diarsipkan.')
                        ->body('Produk yang sudah diarsipkan tidak dapat dicatat sebagai penjualan baru. Pulihkan produk terlebih dahulu jika ingin menjualnya kembali.')
                        ->danger()
                        ->send();

                    return;
                }

                if ($record->stock < $data['quantity']) {
                    Notification::make()
                        ->title('Stok tidak cukup.')
                        ->danger()
                        ->send();

                    return;
                }

                $cost = (float) $record->cost_price;
                $sellingPrice = (float) $record->price;
                $negotiatedPrice = (float) $data['negotiated_price'];
                $quantity = (int) $data['quantity'];

                Sale::create([
                    'sales_channel' => 'internal',
                    'product_id' => $record->id,

                    'product_name_snapshot' => $record->name,
                    'product_sku_snapshot' => $record->serial_number ?: $record->shopee_sku,
                    'product_shopee_item_id_snapshot' => $record->shopee_item_id,
                    'product_shopee_model_id_snapshot' => $record->shopee_model_id,

                    'quantity' => $quantity,
                    'cost_price' => $cost,
                    'selling_price' => $sellingPrice,
                    'negotiated_price' => $negotiatedPrice,
                    'total_profit' => ($negotiatedPrice - $cost) * $quantity,
                    'customer_info' => $data['customer_info'],
                    'transaction_date' => $data['transaction_date'],
                    'external_status' => 'completed',
                ]);

                $record->decrement('stock', $quantity);
                $record->refresh();

                if ($record->canSyncShopeeStock()) {
                    SyncProductStockToShopeeJob::dispatch($record->id);
                }

                Notification::make()
                    ->title('Penjualan berhasil dicatat.')
                    ->success()
                    ->send();
            });
    }

    private static function publishToShopeeAction(): Action
    {
        return Action::make('publish_to_shopee')
            ->label('Publish to Shopee')
            ->icon('heroicon-o-cloud-arrow-up')
            ->color('info')
            ->requiresConfirmation()
            ->visible(fn(Product $record): bool => !$record->trashed() && !$record->isPublishedToShopee())
            ->action(function (Product $record) {
                if (!$record->canPublishToShopee()) {
                    Notification::make()
                        ->title('Data Shopee belum lengkap.')
                        ->body('Lengkapi toko, kategori Shopee, brand, logistik, berat, dimensi, gambar, nama, deskripsi, harga, dan stok sebelum publish.')
                        ->danger()
                        ->send();

                    return;
                }

                PublishProductToShopeeJob::dispatch($record->id);

                Notification::make()
                    ->title('Produk dikirim ke antrean publish Shopee.')
                    ->success()
                    ->send();
            });
    }

    private static function syncShopeeStockAction(): Action
    {
        return Action::make('sync_shopee_stock')
            ->label('Sync Stok Shopee')
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->requiresConfirmation()
            ->visible(fn(Product $record): bool => !$record->trashed() && filled($record->shopee_item_id))
            ->action(function (Product $record) {
                if (!$record->canSyncShopeeStock()) {
                    Notification::make()
                        ->title('Produk belum dikonfigurasi untuk Shopee.')
                        ->body('Pastikan produk sudah publish atau sudah terhubung ke item Shopee yang valid.')
                        ->danger()
                        ->send();

                    return;
                }

                SyncProductStockToShopeeJob::dispatch($record->id);

                Notification::make()
                    ->title('Sinkronisasi stok dikirim ke queue.')
                    ->success()
                    ->send();
            });
    }

    private static function deleteFromShopeeAction(): Action
    {
        return Action::make('delete_from_shopee')
            ->label('Hapus dari Shopee')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn(Product $record): bool => !$record->trashed() && filled($record->shopee_item_id) && filled($record->shopee_shop_id))
            ->modalHeading('Hapus produk dari Shopee?')
            ->modalDescription('Produk akan dihapus dari Shopee. Setelah item Shopee dihapus, stok tidak dapat disinkronkan lagi ke item tersebut.')
            ->action(function (Product $record) {
                try {
                    DeleteShopeeItemJob::dispatchSync(
                        productId: $record->id,
                        shopeeShopId: (int) $record->shopee_shop_id,
                        itemId: (int) $record->shopee_item_id,
                    );

                    Notification::make()
                        ->title('Produk berhasil dihapus dari Shopee.')
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Gagal menghapus produk dari Shopee.')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    private static function deleteFromShopeeAndInternalAction(): Action
    {
        return Action::make('delete_from_shopee_and_internal')
            ->label('Hapus Shopee + Internal')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->visible(fn(Product $record): bool => !$record->trashed() && filled($record->shopee_item_id) && filled($record->shopee_shop_id))
            ->modalHeading('Hapus produk dari Shopee dan Bengawan?')
            ->modalDescription('Aksi ini akan menghapus item Shopee terlebih dahulu. Jika berhasil, produk internal juga akan dihapus.')
            ->action(function (Product $record) {
                try {
                    DeleteShopeeItemJob::dispatchSync(
                        productId: $record->id,
                        shopeeShopId: (int) $record->shopee_shop_id,
                        itemId: (int) $record->shopee_item_id,
                    );

                    $record->delete();

                    Notification::make()
                        ->title('Produk berhasil dihapus dari Shopee dan Bengawan.')
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Gagal menghapus produk.')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
