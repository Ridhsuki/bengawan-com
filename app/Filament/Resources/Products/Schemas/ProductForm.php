<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Produk')->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set) {
                            $set('slug', Str::slug($state));
                        }),
                    TextInput::make('slug')->required(),
                    FileUpload::make('image')->directory('products')->image(),
                    RichEditor::make('description')->columnSpanFull(),
                ]),

                Section::make('Harga & Stok')->schema([
                    TextInput::make('price')->numeric()->prefix('Rp')->required(),
                    TextInput::make('discount_price')
                        ->numeric()
                        ->prefix('Rp')
                        ->helperText('Isi jika produk sedang diskon. Kosongkan jika harga normal.'),
                    TextInput::make('stock')->numeric()->default(1),
                    Toggle::make('is_active')->default(true),
                ])->columns(2),

                Section::make('Marketplace Links')->schema([
                    TextInput::make('link_shopee')->url()->prefixIcon('heroicon-o-shopping-bag'),
                    TextInput::make('link_tokopedia')->url()->prefixIcon('heroicon-o-shopping-cart'),
                ])->columns(2),
            ]);
    }
}
