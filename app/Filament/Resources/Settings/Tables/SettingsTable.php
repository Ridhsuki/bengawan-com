<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Company Logo')->tooltip('This logo is predefined and cannot be altered through the UI. To change it, please update the logo file in the source code.')
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        return asset('assets/img/about-logo.png');
                    })->extraImgAttributes([
                            'title' => 'Company Logo',
                            'loading' => 'lazy',
                        ]),
                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->icon('heroicon-o-building-office'),
                TextColumn::make('about_desc')->label('About')
                    ->limit(23)->icon('heroicon-o-document-text'),
                TextColumn::make('phone')
                    ->icon('heroicon-o-phone'),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ])
            ])
            ->paginated(false);
    }
}
