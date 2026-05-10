<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
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

                        TextInput::make('shopee_item_id')
                            ->label('Shopee Item ID')
                            ->numeric()
                            ->helperText('Ambil dari Seller Centre atau hasil API get_item_list.'),

                        TextInput::make('shopee_model_id')
                            ->label('Shopee Model ID')
                            ->numeric()
                            ->default(0)
                            ->helperText('Isi 0 jika produk Shopee tidak memiliki variasi.'),

                        TextInput::make('shopee_sku')
                            ->label('Shopee SKU')
                            ->maxLength(255)
                            ->helperText('Gunakan SKU yang sama agar mapping lebih mudah diaudit.'),

                        Toggle::make('sync_shopee_stock')
                            ->label('Aktifkan Sinkronisasi Stok Shopee')
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
