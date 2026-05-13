<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Product Overview')
                    ->icon('heroicon-o-archive-box')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Product Name')
                            ->weight(FontWeight::Bold)
                            ->placeholder('-'),
                        TextEntry::make('category.name')
                            ->label('Category')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->html(),
                        ImageEntry::make('image')
                            ->label('Featured Image')
                            ->disk('public')
                            ->imageWidth(480)
                            ->imageHeight(240)
                            ->alignCenter()
                            ->columnSpanFull()
                            ->getStateUsing(fn($record) => $record->image
                                ? asset("storage/{$record->image}")
                                : asset('assets/img/no-image.webp'))
                            ->extraImgAttributes(['title' => 'Featured Image', 'loading' => 'lazy', 'style' => 'border-radius: 0.375rem; object-fit: cover; shadow: 0 1px 3px rgba(0,0,0,0.1);']),

                    ]),

                Section::make('Price & Stock')
                    ->icon('heroicon-o-currency-dollar')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('serial_number')
                            ->label('Serial Number')
                            ->badge()->color('gray'),
                        TextEntry::make('cost_price')
                            ->label('Harga Modal')
                            ->money('IDR')
                            ->placeholder('-'),
                        TextEntry::make('price')
                            ->label('Harga Jual')
                            ->money('IDR'),
                        TextEntry::make('discount_price')
                            ->label('Harga Diskon')
                            ->money('IDR')
                            ->placeholder('-'),
                        TextEntry::make('discount_percentage')
                            ->label('Discount %')
                            ->badge()
                            ->color('success')
                            ->formatStateUsing(fn($state) => $state ? $state . '%' : '-'),
                        TextEntry::make('stock')
                            ->label('Stok')
                            ->badge()
                            ->color(fn($state) => match (true) {
                                $state <= 5 => 'danger',
                                $state <= 20 => 'warning',
                                default => 'success',
                            }),
                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn(bool $state) => $state ? 'Published' : 'Draft/Inactive')
                            ->icon(fn(bool $state) => $state ? 'heroicon-m-check-badge' : 'heroicon-m-x-circle')
                            ->color(fn(bool $state) => $state ? 'success' : 'gray'),
                    ]),

                Section::make('Pengiriman & Link Marketplace')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Fieldset::make('Dimensi & Berat Paket')
                            ->columns(4)
                            ->schema([
                                TextEntry::make('shopee_weight')
                                    ->label('Berat')
                                    ->numeric()
                                    ->suffix(' kg')
                                    ->placeholder('-'),
                                TextEntry::make('shopee_package_length')
                                    ->label('Panjang')
                                    ->numeric()
                                    ->suffix(' cm')
                                    ->placeholder('-'),
                                TextEntry::make('shopee_package_width')
                                    ->label('Lebar')
                                    ->numeric()
                                    ->suffix(' cm')
                                    ->placeholder('-'),
                                TextEntry::make('shopee_package_height')
                                    ->label('Tinggi')
                                    ->numeric()
                                    ->suffix(' cm')
                                    ->placeholder('-'),
                            ]),

                        Fieldset::make('Tautan Eksternal')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('link_shopee')
                                    ->label('Shopee')
                                    ->url(fn($state) => $state)
                                    ->openUrlInNewTab()
                                    ->color('warning')
                                    ->formatStateUsing(fn($state) => $state ? 'Buka di Shopee' : 'Belum ditautkan'),
                                TextEntry::make('link_tokopedia')
                                    ->label('Tokopedia')
                                    ->url(fn($state) => $state)
                                    ->openUrlInNewTab()
                                    ->color('success')
                                    ->formatStateUsing(fn($state) => $state ? 'Buka di Tokopedia' : 'Belum ditautkan'),
                            ]),
                    ]),

                Section::make('Sinkronisasi Shopee')
                    ->icon('heroicon-o-arrow-path')
                    ->collapsed()
                    ->schema([
                        Fieldset::make('Konfigurasi API')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('shopeeShop.shop_name')
                                    ->label('Toko Terhubung')
                                    ->placeholder('Belum dipilih'),
                                TextEntry::make('shopee_sku')
                                    ->label('Shopee SKU')
                                    ->copyable()
                                    ->placeholder('-'),
                                TextEntry::make('shopee_item_id')
                                    ->label('Item ID')
                                    ->copyable()
                                    ->placeholder('Produk Baru'),
                                TextEntry::make('shopee_model_id')
                                    ->label('Model ID')
                                    ->copyable()
                                    ->placeholder('-'),
                            ]),

                        Fieldset::make('Kategorisasi Shopee')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('shopee_category_id')
                                    ->label('Kategori ID')
                                    ->placeholder('-'),
                                TextEntry::make('shopee_brand_name')
                                    ->label('Brand')
                                    ->placeholder('-'),
                                TextEntry::make('shopee_condition')
                                    ->label('Kondisi')
                                    ->badge()
                                    ->color(fn($state) => $state === 'NEW' ? 'success' : 'warning'),
                                TextEntry::make('shopee_logistic_id')
                                    ->label('ID Logistik')
                                    ->placeholder('-'),
                            ]),

                        Fieldset::make('Status Sinkronisasi')
                            ->columns(3)
                            ->schema([
                                IconEntry::make('sync_shopee_stock')
                                    ->label('Auto-Sync Aktif')
                                    ->boolean(),
                                TextEntry::make('shopee_stock')
                                    ->label('Stok di Shopee')
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('-'),
                                TextEntry::make('shopee_publish_status')
                                    ->label('Publish Status')
                                    ->badge()
                                    ->color(fn($state) => match ($state) {
                                        'SUCCESS' => 'success',
                                        'FAILED' => 'danger',
                                        'PROCESSING' => 'warning',
                                        default => 'gray',
                                    })
                                    ->placeholder('-'),
                                TextEntry::make('shopee_item_status')
                                    ->label('Item Status')
                                    ->badge()
                                    ->placeholder('-'),
                                TextEntry::make('shopee_sync_status')
                                    ->label('Sync Status')
                                    ->badge()
                                    ->placeholder('-'),
                            ]),

                        Fieldset::make('Log Error')
                            ->columns(1)
                            ->visible(fn($record) => !empty($record->shopee_publish_error) || !empty($record->shopee_sync_error))
                            ->schema([
                                TextEntry::make('shopee_publish_error')
                                    ->label('Error Publish Terakhir')
                                    ->color('danger')
                                    ->visible(fn($state) => filled($state)),
                                TextEntry::make('shopee_sync_error')
                                    ->label('Error Sync Terakhir')
                                    ->color('danger')
                                    ->visible(fn($state) => filled($state)),
                            ]),
                    ]),

                Section::make('Product Gallery')
                    ->icon('heroicon-o-photo')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('images_gallery')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->html()
                            ->getStateUsing(function ($record) {
                                if (!$record->images || $record->images->isEmpty()) {
                                    return '<div style="text-align: center; padding: 20px; color: #6b7280; background: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">
                        Tidak ada gambar galeri.
                    </div>';
                                }

                                $galleryHtml = collect($record->images)->map(function ($img) {
                                    $imageUrl = asset('storage/' . $img->image);
                                    return "
                    <div style='
                        position: relative;
                        aspect-ratio: 1 / 1;
                        overflow: hidden;
                        border-radius: 12px;
                        border: 1px solid #e5e7eb;
                        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                        background-color: #fff;
                    '>
                        <a href='{$imageUrl}' target='_blank' style='display: block; width: 100%; height: 100%;'>
                            <img
                                src='{$imageUrl}'
                                loading='lazy'
                                style='
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                    transition: transform 0.3s ease;
                                '
                                onmouseover='this.style.transform=\"scale(1.1)\"'
                                onmouseout='this.style.transform=\"scale(1.0)\"'
                                alt='Gallery Image'
                            />
                        </a>
                    </div>";
                                })->implode('');

                                return "
                <div style='
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                    gap: 16px;
                    width: 100%;
                '>
                    {$galleryHtml}
                </div>";
                            }),
                    ]),

                Section::make('Timestamps')
                    ->icon('heroicon-o-clock')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Diupdate Pada')
                            ->dateTime('d M Y H:i'),
                    ]),
            ]);
    }
}
