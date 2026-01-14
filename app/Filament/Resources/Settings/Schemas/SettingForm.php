<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->required()
                    ->hint('Will be displayed in footer')
                    ->default('Bengawan Computer'),

                TextInput::make('phone')
                    ->label('WhatsApp Number')
                    ->tel()
                    ->numeric(),

                Textarea::make('address')
                    ->label('Footer Address')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('google_maps_link')
                    ->label('Google Maps Link')
                    ->url()
                    ->columnSpanFull(),

                TextInput::make('about_title')
                    ->label('About Page Title')
                    ->placeholder('Tentang Kami')
                    ->columnSpanFull(),

                Textarea::make('about_desc')
                    ->label('About Page Description')
                    ->rows(5)
                    ->columnSpanFull(),

                Repeater::make('banners')
                    ->label('Main Sliders / Banners')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('banners')
                            ->label('Banner Image')
                            ->getUploadedFileNameForStorageUsing(
                                fn(TemporaryUploadedFile $file): string => 'banner-' . time() . '.' . $file->getClientOriginalExtension()
                            ),
                        TextInput::make('title')
                            ->label('Caption Title (Optional)'),
                        TextInput::make('url')
                            ->label('Link URL (Optional)')
                            ->url()
                            ->placeholder('https://...'),
                    ])
                    ->maxItems(3)
                    ->grid(3)
                    ->columnSpanFull(),

                Repeater::make('social_media')
                    ->schema([
                        Select::make('platform')
                            ->label('Platform')
                            ->options([
                                'instagram' => 'Instagram',
                                'facebook' => 'Facebook',
                                'tiktok' => 'TikTok',
                                'whatsapp' => 'WhatsApp',
                                'youtube' => 'YouTube',
                                'twitter' => 'Twitter',
                                'linkedin' => 'LinkedIn',
                                'tokopedia' => 'Tokopedia',
                                'shopee' => 'Shopee',
                            ])
                            ->required(),
                        TextInput::make('url')
                            ->label('Profile URL')
                            ->url()
                            ->required()
                            ->placeholder('https://...'),
                    ])
                    ->label('Social Media Links')
                    ->maxItems(3)
                    ->grid(3)
                    ->columnSpanFull(),
            ]);
    }
}
