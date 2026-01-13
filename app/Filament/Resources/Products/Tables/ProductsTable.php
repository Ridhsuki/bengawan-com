<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()->limit(40)
                    ->extraAttributes(['class' => 'font-semibold']),

                TextColumn::make('slug')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(fn($record) => $record->slug)
                    ->extraAttributes(['class' => 'text-gray-500 text-sm']),
                TextColumn::make('category.name')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->extraAttributes(['class' => 'font-semibold text-gray-800']),

                ImageColumn::make('image')
                    ->square()
                    ->imageWidth(90)
                    ->imageHeight(45)
                    ->getStateUsing(function ($record) {
                        if ($record->image) {
                            return asset("storage/{$record->image}");
                        }
                        return asset('assets/img/no-image.webp');
                    })
                    ->extraImgAttributes(['title' => 'Articles Image', 'loading' => 'lazy', 'style' => 'border-radius: 0.375rem; object-fit: cover;']),

                TextColumn::make('price')
                    ->money('idr', true)
                    ->sortable()
                    ->extraAttributes(['class' => 'font-bold text-gray-900']),

                TextColumn::make('discount_price')
                    ->placeholder('no discount price')
                    ->money('idr', true)
                    ->sortable()
                    ->extraAttributes(['class' => 'text-red-600 font-bold'])
                    ->toggleable(),

                BadgeColumn::make('stock')
                    ->colors([
                        'danger' => fn($state) => $state <= 5,
                        'warning' => fn($state) => $state > 5 && $state <= 20,
                        'success' => fn($state) => $state > 20,
                    ])
                    ->sortable()
                    ->numeric(),

                TextColumn::make('link_shopee')
                    ->label('Shopee')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => $state ? 'Link' : null)
                    ->url(fn($state) => $state)
                    ->openUrlInNewTab()
                    ->alignCenter(),

                TextColumn::make('link_tokopedia')
                    ->label('Tokopedia')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => $state ? 'Link' : null)
                    ->url(fn($state) => $state)
                    ->openUrlInNewTab()
                    ->alignCenter(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable()
                    ->colors([
                        'success' => fn($state) => $state,
                        'danger' => fn($state) => !$state,
                    ]),

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
                    ->placeholder('Select Category')
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
