<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\Pages\ManageSales;
use App\Models\Sale;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersResetActionPosition;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;
    protected static ?string $pluralLabel = 'Laporan Penjualan';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal')->native(false),
                        DatePicker::make('until')->label('Sampai Tanggal')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    })
            ], layout: FiltersLayout::Modal)
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->summarize(Sum::make()->label('Total Unit')),
                TextColumn::make('selling_price')
                    ->label('Harga Jual')
                    ->money('IDR'),
                TextColumn::make('cost_price')
                    ->label('Modal')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total_profit')
                    ->label('Keuntungan')
                    ->money('IDR')
                    ->summarize(Sum::make()->label('Total Laba'))
                    ->color('success')
                    ->weight('bold'),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSales::route('/'),
        ];
    }
}
