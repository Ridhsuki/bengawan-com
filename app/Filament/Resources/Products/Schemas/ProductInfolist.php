<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
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
                        TextEntry::make('price')
                            ->label('Price')
                            ->money('IDR'),
                        TextEntry::make('discount_price')
                            ->label('Discount Price')
                            ->money('IDR')
                            ->placeholder('-'),
                        TextEntry::make('discount_percentage')
                            ->label('Discount %')
                            ->badge()
                            ->color('success')
                            ->formatStateUsing(fn($state) => $state ? $state . '%' : '-'),
                        TextEntry::make('stock')
                            ->label('Stock')
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

                Section::make('Marketplace Links')
                    ->icon('heroicon-o-link')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('link_shopee')
                            ->label('Shopee')
                            ->url(fn($state) => $state)
                            ->openUrlInNewTab()
                            ->color('warning')
                            ->formatStateUsing(fn($state) => $state ? 'Shopee' : '-'),
                        TextEntry::make('link_tokopedia')
                            ->label('Tokopedia')
                            ->url(fn($state) => $state)
                            ->openUrlInNewTab()
                            ->color('success')
                            ->formatStateUsing(fn($state) => $state ? 'Tokopedia' : '-'),
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
                            ->label('Created At')
                            ->dateTime('d M Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('d M Y H:i'),
                    ]),
            ]);
    }
}
