<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use App\Services\Shopee\ShopeeCatalogService;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Produk')->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required()
                        ->label('Kategori Produk')
                        ->prefixIcon('heroicon-o-tag')
                        ->columnSpan(1),
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, $state) {
                            $slug = Str::slug($state);
                            $existingSlug = Product::where('slug', $slug)->first();
                            if ($existingSlug) {
                                $slug = $slug . '-' . uniqid();
                            }
                            $set('slug', $slug);
                        })
                        ->label('Nama Produk')
                        ->prefixIcon('heroicon-o-clipboard'),
                    TextInput::make('slug')
                        ->hint('Generated from the title automatically.')
                        ->required()->readOnly()->disabled()->dehydrated(true)
                        ->label('Slug Produk')
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->columnSpanFull()->rows(7)
                        ->label('Deskripsi Produk'),
                ])
                    ->columnSpanFull(),

                Section::make('Gambar dan Galeri')->schema([
                    FileUpload::make('image')
                        ->label('Thumbnail Utama (Cover)')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('products')
                        ->required()
                        ->columnSpanFull()
                        ->helperText('Upload gambar utama produk'),
                    Repeater::make('images')
                        ->relationship()
                        ->label('Galeri Tambahan')
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->directory('products/gallery')
                                ->required()
                                ->label('Tambah Foto Galeri'),
                        ])
                        ->grid(2)
                        ->defaultItems(0)
                        ->addActionLabel('Tambah Foto Galeri')->columnSpanFull(),
                ])->columnSpanFull(),
                Section::make('Harga & Stok')->schema([
                    TextInput::make('serial_number')
                        ->label('Serial Number')
                        ->nullable(),
                    TextInput::make('cost_price')
                        ->label('Harga Modal')
                        ->prefix('Rp')
                        ->numeric()
                        ->required(),
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('Rp')
                        ->required()
                        ->label('Harga Jual')
                        ->columnSpan(1),
                    TextInput::make('discount_price')
                        ->numeric()
                        ->prefix('Rp')
                        ->helperText('Isi jika produk sedang diskon. Kosongkan jika harga normal.')
                        ->label('Harga Diskon')
                        ->columnSpan(1),
                    TextInput::make('stock')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->label('Stok Produk')
                        ->columnSpan(1),
                    Toggle::make('is_active')
                        ->default(true)
                        ->label('Aktif')
                        ->columnSpan(1),
                ])->columnSpanFull(),

                Section::make('Marketplace & Sinkronisasi Shopee')
                    ->schema([
                        TextInput::make('link_shopee')
                            ->url()
                            ->label('Link Shopee')
                            ->maxLength(2001)
                            ->prefixIcon('heroicon-o-shopping-bag'),

                        TextInput::make('link_tokopedia')
                            ->url()
                            ->label('Link Tokopedia')
                            ->maxLength(2001)
                            ->prefixIcon('heroicon-o-shopping-cart'),

                        Select::make('shopee_shop_id')
                            ->label('Toko Shopee Terhubung')
                            ->relationship('shopeeShop', 'shop_name')
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih toko Shopee yang sudah dihubungkan melalui Shopee Open Platform.'),

                        Select::make('shopee_item_id')
                            ->label('Shopee Item')
                            ->searchable()
                            ->preload(false)
                            ->getSearchResultsUsing(
                                fn(string $search): array => app(ShopeeCatalogService::class)->itemOptions($search)
                            )
                            ->getOptionLabelUsing(
                                fn($value): ?string => $value ? app(ShopeeCatalogService::class)->itemLabel((int) $value) : null
                            )
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('shopee_model_id', 0);
                            })
                            ->helperText('Pilih hanya jika ingin menghubungkan produk Bengawan ke produk Shopee yang sudah ada. Untuk publish produk baru, kosongkan.')
                            ->rules([
                                function (Get $get, $record) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                        if (blank($value)) {
                                            return;
                                        }
                                        $exists = Product::query()
                                            ->where('shopee_shop_id', $get('shopee_shop_id'))
                                            ->where('shopee_item_id', $value)
                                            ->where('shopee_model_id', (int) ($get('shopee_model_id') ?? 0))
                                            ->when($record, fn($query) => $query->whereKeyNot($record->id))
                                            ->exists();

                                        if ($exists) {
                                            $fail('Item Shopee dan model ini sudah terhubung ke produk Bengawan lain. Pilih item/model lain.');
                                        }
                                    };
                                },
                            ]),

                        Select::make('shopee_model_id')
                            ->label('Shopee Model')
                            ->searchable()
                            ->required()
                            ->options(function (Get $get): array {
                                return app(ShopeeCatalogService::class)->modelOptions(
                                    $get('shopee_item_id') ? (int) $get('shopee_item_id') : null
                                );
                            })
                            ->getOptionLabelUsing(function ($value, Get $get): ?string {
                                return app(ShopeeCatalogService::class)->modelLabel(
                                    $get('shopee_item_id') ? (int) $get('shopee_item_id') : null,
                                    $value !== null ? (int) $value : 0
                                );
                            })
                            ->default(0)
                            ->dehydrated(true)
                            ->helperText('Untuk produk baru tanpa variasi, biarkan 0. Jika menghubungkan item existing dan ada variasi, pilih model dari Shopee.')
                            ->rules([
                                function (Get $get, $record) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                        if (blank($get('shopee_item_id'))) {
                                            return;
                                        }

                                        $exists = Product::query()
                                            ->where('shopee_shop_id', $get('shopee_shop_id'))
                                            ->where('shopee_item_id', $get('shopee_item_id'))
                                            ->where('shopee_model_id', (int) ($value ?? 0))
                                            ->when($record, fn($query) => $query->whereKeyNot($record->id))
                                            ->exists();

                                        if ($exists) {
                                            $fail('Model Shopee ini sudah dipakai oleh produk Bengawan lain.');
                                        }
                                    };
                                },
                            ]),

                        TextInput::make('shopee_sku')
                            ->label('Shopee SKU')
                            ->maxLength(255)
                            ->helperText('Gunakan SKU yang sama agar mapping lebih mudah diaudit.'),

                        Select::make('shopee_category_id')
                            ->label('Shopee Category')
                            ->searchable()
                            ->preload()
                            ->options(fn(): array => app(ShopeeCatalogService::class)->categoryOptions())
                            ->getSearchResultsUsing(
                                fn(string $search): array => app(ShopeeCatalogService::class)->categoryOptions($search)
                            )
                            ->getOptionLabelUsing(
                                fn($value): ?string => $value ? app(ShopeeCatalogService::class)->categoryLabel((int) $value) : null
                            )
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('shopee_brand_id', 0);
                                $set('shopee_brand_name', 'NoBrand');
                            })
                            ->helperText('Pilih kategori Shopee. Jika data API belum lengkap, gunakan kategori fallback laptop untuk testing.'),

                        Select::make('shopee_brand_id')
                            ->label('Shopee Brand')
                            ->searchable()
                            ->options(function (Get $get): array {
                                return app(ShopeeCatalogService::class)->brandOptions(
                                    $get('shopee_category_id') ? (int) $get('shopee_category_id') : null
                                );
                            })
                            ->getSearchResultsUsing(function (string $search, Get $get): array {
                                return app(ShopeeCatalogService::class)->brandOptions(
                                    $get('shopee_category_id') ? (int) $get('shopee_category_id') : null,
                                    $search
                                );
                            })
                            ->getOptionLabelUsing(function ($value, Get $get): ?string {
                                return app(ShopeeCatalogService::class)->brandLabel(
                                    $value !== null ? (int) $value : 0,
                                    $get('shopee_category_id') ? (int) $get('shopee_category_id') : null
                                );
                            })
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $label = app(ShopeeCatalogService::class)->brandLabel(
                                    $state !== null ? (int) $state : 0,
                                    $get('shopee_category_id') ? (int) $get('shopee_category_id') : null
                                );

                                $set('shopee_brand_name', $label ?: 'NoBrand');
                            })
                            ->helperText('Pilih brand dari kategori Shopee. Gunakan NoBrand jika belum tersedia.'),

                        TextInput::make('shopee_brand_name')
                            ->label('Shopee Brand Name')
                            ->default('NoBrand')
                            ->readOnly()
                            ->maxLength(255),

                        Select::make('shopee_condition')
                            ->label('Kondisi Shopee')
                            ->options([
                                'NEW' => 'NEW',
                                'USED' => 'USED',
                            ])
                            ->default('NEW'),

                        TextInput::make('shopee_weight')
                            ->label('Berat Paket Shopee')
                            ->numeric()
                            ->suffix('kg')
                            ->helperText('Contoh: 1.43'),

                        TextInput::make('shopee_package_length')
                            ->label('Panjang Paket')
                            ->numeric()
                            ->suffix('cm'),

                        TextInput::make('shopee_package_width')
                            ->label('Lebar Paket')
                            ->numeric()
                            ->suffix('cm'),

                        TextInput::make('shopee_package_height')
                            ->label('Tinggi Paket')
                            ->numeric()
                            ->suffix('cm'),

                        Select::make('shopee_logistic_id')
                            ->label('Shopee Logistic')
                            ->columnSpan(2)
                            ->searchable()
                            ->preload()
                            ->options(fn(): array => app(ShopeeCatalogService::class)->logisticOptions())
                            ->getSearchResultsUsing(
                                fn(string $search): array => app(ShopeeCatalogService::class)->logisticOptions($search)
                            )
                            ->getOptionLabelUsing(
                                fn($value): ?string => $value ? app(ShopeeCatalogService::class)->logisticLabel((int) $value) : null
                            )
                            ->helperText('Pilih channel logistik aktif dari Shopee. Wajib untuk publish produk baru.'),

                        TextInput::make('shopee_publish_status')
                            ->label('Status Publish Shopee')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Status publish produk ke Shopee.'),

                        TextInput::make('shopee_item_status')
                            ->label('Status Item Shopee')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('-')
                            ->helperText('Status item Shopee hasil sinkronisasi atau rekonsiliasi.'),

                        Textarea::make('shopee_publish_error')
                            ->label('Error Publish Shopee')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Toggle::make('sync_shopee_stock')
                            ->label('Aktifkan Sinkronisasi Stok Shopee')
                            ->columnSpan(2)
                            ->default(false)
                            ->helperText('Jika aktif, perubahan stok di website akan dikirim ke Shopee.'),

                        TextInput::make('shopee_stock')
                            ->label('Stok Terakhir dari Shopee')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('shopee_sync_status')
                            ->label('Status Sync')
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('shopee_sync_error')
                            ->label('Error Sync Terakhir')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
