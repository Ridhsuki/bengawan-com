<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Storage;

class SettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('General Information')
                    ->columns(1)
                    ->icon('heroicon-o-building-office-2')
                    ->schema([
                        ImageEntry::make('logo')
                            ->label('Company Logo')
                            ->disk('public')
                            ->alignCenter()
                            ->getStateUsing(fn($record) => asset('assets/img/about-logo.png'))
                            ->extraImgAttributes([
                                'title' => 'Company Logo',
                                'loading' => 'lazy',
                                'style' => 'border-radius: 0.375rem; object-fit: cover;'
                            ])
                            ->hint('This logo is predefined and cannot be altered through the UI. To change it, please update the logo file in the source code.')
                        ,
                        TextEntry::make('company_name')
                            ->label('Company Name')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('address')
                            ->label('Office Address')
                            ->placeholder('-')
                            ->icon('heroicon-o-map-pin'),
                    ]),
                Section::make('Description & Strategy')
                    ->columns(1)
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        TextEntry::make('about_title')
                            ->label('About Title')
                            ->placeholder('-'),
                        TextEntry::make('about_desc')
                            ->label('About Description')
                            ->placeholder('-'),
                    ]),
                Section::make('Home Banners')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        TextEntry::make('banners_html')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->html()
                            ->getStateUsing(function ($record) {
                                $banners = $record->banners;

                                if (empty($banners)) {
                                    return '<div style="text-align: center; padding: 20px; color: #6b7280; background: #f9fafb; border-radius: 8px; border: 1px dashed #d1d5db;">
                        Tidak ada banner yang diupload.
                    </div>';
                                }

                                $galleryHtml = collect($banners)->map(function ($banner) {
                                    $imagePath = $banner['image'] ?? '';

                                    $imageUrl = asset('storage/' . $imagePath);

                                    $title = $banner['title'] ?? '';
                                    $url = $banner['url'] ?? null;

                                    $wrapStart = $url ? "<a href='{$url}' target='_blank' style='text-decoration: none; color: inherit; display: block; height: 100%;'>" : "<div style='display: block; height: 100%;'>";
                                    $wrapEnd = $url ? "</a>" : "</div>";

                                    $captionHtml = $title
                                        ? "<div style='padding: 10px; border-top: 1px solid #f3f4f6; background: #fafafa; font-size: 0.85rem; font-weight: 600; color: #374151;'>{$title}</div>"
                                        : "";

                                    return "
                    <div style='
                        position: relative;
                        overflow: hidden;
                        border-radius: 12px;
                        border: 1px solid #e5e7eb;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                        background-color: #fff;
                        transition: box-shadow 0.3s ease;
                    ' onmouseover='this.style.boxShadow=\"0 4px 6px -1px rgba(0, 0, 0, 0.1)\"' onmouseout='this.style.boxShadow=\"0 1px 2px rgba(0,0,0,0.05)\"'>

                        {$wrapStart}
                            <div style='
                                width: 100%;
                                aspect-ratio: 16/9;
                                overflow: hidden;
                                background-color: #f3f4f6;
                            '>
                                <img
                                    src='{$imageUrl}'
                                    loading='lazy'
                                    style='
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                        transition: transform 0.5s ease;
                                    '
                                    onmouseover='this.style.transform=\"scale(1.05)\"'
                                    onmouseout='this.style.transform=\"scale(1.0)\"'
                                    alt='{$title}'
                                />
                            </div>
                            {$captionHtml}
                        {$wrapEnd}

                    </div>";
                                })->implode('');

                                return "
                <div style='
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                    gap: 16px;
                    width: 100%;
                '>
                    {$galleryHtml}
                </div>";
                            }),
                    ]),
                Section::make('Contact & Social Media')
                    ->columns(1)
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextEntry::make('phone')
                            ->label('Phone Number')
                            ->placeholder('-')
                            ->icon('heroicon-o-phone'),
                        RepeatableEntry::make('social_media')
                            ->label('Social Media Links')
                            ->schema([
                                TextEntry::make('platform')
                                    ->badge()
                                    ->color('primary'),
                                TextEntry::make('url')
                                    ->icon('heroicon-o-arrow-top-right-on-square')
                                    ->url(fn($state) => $state)
                                    ->openUrlInNewTab(),
                            ])
                            ->grid(3)
                            ->placeholder('none'),
                    ]),
                Section::make('Metadata')
                    ->columns(2)
                    ->icon('heroicon-o-clock')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
                    ]),
            ]);
    }
}
