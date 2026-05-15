<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\Pages\ManageSales;
use App\Models\Sale;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected static ?string $pluralLabel = 'Laporan Penjualan Internal';

    protected static ?string $navigationLabel = 'Internal Sales Report';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('sales_channel', 'internal');
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
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->native(false),

                        DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
            ])
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('customer_info')
                    ->label('Customer')
                    ->limit(18)
                    ->searchable()
                    ->icon('heroicon-m-user'),

                TextColumn::make('product_name_snapshot')
                    ->label('Produk')
                    ->state(fn(Sale $record): string => $record->product_name_snapshot ?: ($record->product?->name ?? 'Produk sudah dihapus'))
                    ->searchable()
                    ->limit(32)
                    ->description(fn(Sale $record): ?string => $record->product_sku_snapshot ? 'SKU: ' . $record->product_sku_snapshot : null),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->badge()
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Unit')),

                TextColumn::make('cost_price')
                    ->label('Modal')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('selling_price')
                    ->label('Harga Awal')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('negotiated_price')
                    ->label('Harga/Unit')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('total_omset')
                    ->label('Total Omzet')
                    ->state(fn(Sale $record): float => (float) $record->negotiated_price * (int) $record->quantity)
                    ->money('IDR')
                    ->weight('bold')
                    ->color('primary')
                    ->summarize(
                        Summarizer::make()
                            ->label('Total Pendapatan')
                            ->using(fn($query) => $query->sum(DB::raw('negotiated_price * quantity')))
                            ->money('IDR')
                    ),

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
