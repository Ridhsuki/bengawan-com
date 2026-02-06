<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\Pages\ManageSales;
use App\Models\Sale;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Columns\Summarizers\Summarizer;

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
            ])
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('customer_info')
                    ->label('Customer')
                    ->limit(15)
                    ->searchable()
                    ->icon('heroicon-m-user'),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->limit(20),
                TextColumn::make('quantity')
                    ->label('Qty')->badge()
                    ->summarize(Sum::make()->label('Total Unit')),
                TextColumn::make('cost_price')
                    ->label('Modal')
                    ->money('IDR'),
                TextColumn::make('selling_price')
                    ->label('Harga Awal')
                    ->money('IDR'),
                TextColumn::make('negotiated_price')
                    ->label('Harga/Unit')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('total_omset')
                    ->label('Total Omset')
                    ->state(function (Sale $record) {
                        return $record->negotiated_price * $record->quantity;
                    })
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
