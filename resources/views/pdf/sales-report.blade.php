<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body{font-family:'Helvetica','Arial',sans-serif;font-size:10pt;color:#333;margin:0;padding:0}.header-table{width:100%;border-bottom:2px solid #333;padding-bottom:10px;margin-bottom:20px}.logo-container{width:20%;vertical-align:middle}.company-info{width:80%;text-align:right;vertical-align:middle}.company-name{font-size:16pt;font-weight:700;margin:0;text-transform:uppercase}.company-detail{margin:2px 0;font-size:9pt;color:#555}.report-title{text-align:center;margin-bottom:20px}.report-title h2{margin:0;font-size:14pt;text-transform:uppercase;letter-spacing:1px}.report-period{font-size:10pt;color:#666;margin-top:5px}.data-table{width:100%;border-collapse:collapse;margin-bottom:20px;font-size:9pt}.data-table th{background-color:#e5e7eb;color:#1f2937;font-weight:700;text-transform:uppercase;font-size:8pt;padding:10px 8px;border-top:1px solid #9ca3af;border-bottom:1px solid #9ca3af}.data-table td{border-bottom:1px solid #e5e7eb;padding:8px;vertical-align:middle}.data-table tr:nth-child(even){background-color:#f9fafb}.text-right{text-align:right}.text-center{text-align:center}.font-bold{font-weight:700}.text-sm{font-size:8pt;color:#666}.text-omset{color:#000;font-weight:700}.text-profit{color:#166534;font-weight:700}.summary-wrapper{width:100%;margin-top:20px}.summary-table{width:45%;float:right;border-collapse:collapse}.summary-table td{padding:8px;border-bottom:1px solid #eee}.grand-total-row{border-top:2px solid #333!important;border-bottom:none!important;background-color:#f3f4f6}.grand-total-label{font-size:11pt;font-weight:700}.grand-total-value{font-size:12pt;font-weight:700}.meta-info{position:fixed;bottom:0;left:0;width:100%;font-size:8pt;color:#999;border-top:1px solid #eee;padding-top:5px}
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('assets/img/about-logo.png') }}" alt="Logo" style="width: 200px">
            </td>
            <td class="company-info">
                <h1 class="company-name">{{ $setting->company_name ?? 'Bengawan Komputer' }}</h1>
                <p class="company-detail">{{ $setting->address ?? '-' }}</p>
                <p class="company-detail">Telp: {{ $setting->phone ?? '-' }}</p>
            </td>
        </tr>
    </table>

    <div class="report-title">
        <h2>Laporan Penjualan</h2>
        <div class="report-period">Periode: {{ $dateRange }}</div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="18%">Customer</th>
                <th width="25%">Produk</th>
                <th class="text-center" width="5%">Qty</th>
                <th class="text-right" width="13%">Harga Satuan</th>
                <th class="text-right" width="13%">Total (Omset)</th>
                <th class="text-right" width="13%">Laba</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $index => $sale)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $sale->transaction_date->format('d/m/Y') }}</td>

                    <td>
                        {{ $sale->customer_info ?? 'Umum' }}
                    </td>

                    <td>
                        <span class="font-bold">{{ $sale->product->name }}</span>
                        @if ($sale->product->serial_number)
                            <br><span class="text-sm">SN: {{ $sale->product->serial_number }}</span>
                        @endif
                    </td>

                    <td class="text-center">{{ $sale->quantity }}</td>

                    <td class="text-right">
                        Rp {{ number_format($sale->negotiated_price, 0, ',', '.') }}
                    </td>

                    <td class="text-right text-omset">
                        Rp {{ number_format($sale->negotiated_price * $sale->quantity, 0, ',', '.') }}
                    </td>

                    <td class="text-right text-profit">
                        Rp {{ number_format($sale->total_profit, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-wrapper">
        <table class="summary-table">
            <tr>
                <td>Total Transaksi</td>
                <td class="text-right font-bold">{{ $sales->count() }} Transaksi</td>
            </tr>
            <tr>
                <td>Total Item Terjual</td>
                <td class="text-right font-bold">{{ $sales->sum('quantity') }} Unit</td>
            </tr>
            <tr class="grand-total-row">
                <td class="grand-total-label">Total Pendapatan (Omset)</td>
                <td class="text-right grand-total-value">
                    Rp {{ number_format($sales->sum(fn($s) => $s->negotiated_price * $s->quantity), 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td class="text-success font-bold" style="padding-top: 10px;">Total Laba Bersih</td>
                <td class="text-right text-success font-bold" style="padding-top: 10px;">
                    Rp {{ number_format($sales->sum('total_profit'), 0, ',', '.') }}
                </td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    <div class="meta-info">
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}
    </div>

    <script type="text/php">
    if (isset($pdf)) {
        $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
        $font = $fontMetrics->get_font("helvetica", "normal");
        $size = 8;
        $color = array(0.5, 0.5, 0.5);
        $pdf->page_text(520, 820, $text, $font, $size, $color);
    }
    </script>
</body>

</html>
