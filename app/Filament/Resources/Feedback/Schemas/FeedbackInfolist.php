<?php

namespace App\Filament\Resources\Feedback\Schemas;

use App\Enums\FeedbackStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeedbackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->schema([
                Grid::make(1)
                    ->columnSpan(2)
                    ->schema([
                        Section::make('Guest Information')
                            // ->description('Informasi teknis perangkat pengirim')
                            ->icon('heroicon-o-finger-print')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('ip_address')
                                    ->label('IP Address')
                                    ->icon('heroicon-o-globe-alt')
                                    ->copyable()
                                    ->fontFamily('mono')
                                    ->placeholder('N/A'),

                                TextEntry::make('user_agent')
                                    ->label('Device / User Agent')
                                    ->icon('heroicon-o-device-phone-mobile')
                                    ->limit(50)
                                    ->tooltip(fn ($state) => $state)
                                    ->placeholder('N/A'),
                            ]),

                        Section::make('Message Content')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->columns(1)
                            ->schema([
                                TextEntry::make('message')
                                    ->columnSpanFull()
                                    ->prose()
                                    ->placeholder('N/A'),
                            ]),
                    ]),

                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Status')
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Current Status')
                                    ->badge()
                                    ->color(fn (FeedbackStatus $state) => match ($state) {
                                        FeedbackStatus::NEW => 'danger',
                                        FeedbackStatus::REVIEWED => 'gray',
                                        default => 'gray',
                                    })
                                    ->icon(fn (FeedbackStatus $state) => match ($state) {
                                        FeedbackStatus::NEW => 'heroicon-o-sparkles',
                                        FeedbackStatus::REVIEWED => 'heroicon-o-check-circle',
                                        default => 'heroicon-o-question-mark-circle',
                                    }),
                            ]),

                        Section::make('Timestamps')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Submitted At')
                                    ->dateTime('d M Y, H:i:s')
                                    ->icon('heroicon-o-calendar-days'),
                            ])->grow(false),
                    ]),
            ]);
    }
}
