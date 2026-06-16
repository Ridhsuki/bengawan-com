<?php

namespace App\Actions\Sales;

use App\Models\Sale;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateSaleInvoicePdf
{
    public function execute(Sale $sale): \Barryvdh\DomPDF\PDF
    {
        $sale->loadMissing('product');

        $setting = Setting::first();

        $invoiceNumber = 'NOTA-'
            . ($sale->transaction_date?->format('Ymd') ?? now()->format('Ymd'))
            . '-' . $sale->id;

        $productName = $sale->product_name_snapshot
            ?: ($sale->product?->name ?? 'Produk sudah dihapus');

        $productSku = $sale->product_sku_snapshot
            ?: ($sale->product?->serial_number ?? null);

        return Pdf::loadView('pdf.sale-invoice', [
            'sale' => $sale,
            'setting' => $setting,
            'invoiceNumber' => $invoiceNumber,
            'productName' => $productName,
            'productSku' => $productSku,
        ])->setPaper('a4', 'portrait');
    }

    public static function filename(Sale $sale): string
    {
        $date = $sale->transaction_date?->format('Ymd') ?? now()->format('Ymd');
        $paddedId = str_pad((string) $sale->id, 6, '0', STR_PAD_LEFT);

        return "nota-penjualan-{$date}-{$paddedId}.pdf";
    }
}
