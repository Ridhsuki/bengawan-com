<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Penjualan - {{ $invoiceNumber }}</title>
    <style>
        body{font-family:'Helvetica','Arial',sans-serif;font-size:10pt;color:#333;margin:0;padding:20px;padding-bottom:50px}
        .header-table{width:100%;border-bottom:2px solid #333;padding-bottom:10px;margin-bottom:20px}
        .logo-container{width:20%;vertical-align:middle}
        .company-info{width:80%;text-align:right;vertical-align:middle}
        .company-name{font-size:16pt;font-weight:700;margin:0;text-transform:uppercase}
        .company-detail{margin:2px 0;font-size:9pt;color:#555}
        .invoice-title{text-align:center;margin-bottom:25px}
        .invoice-title h2{margin:0;font-size:14pt;text-transform:uppercase;letter-spacing:1px}
        .invoice-title .invoice-number{font-size:11pt;color:#555;margin-top:4px}
        .info-table{width:100%;margin-bottom:20px;font-size:9.5pt}
        .info-table td{padding:3px 8px;vertical-align:top}
        .info-label{font-weight:700;width:140px;color:#555}
        .info-value{color:#1f2937}
        .data-table{width:100%;border-collapse:collapse;margin-bottom:20px;font-size:9.5pt}
        .data-table th{background-color:#e5e7eb;color:#1f2937;font-weight:700;text-transform:uppercase;font-size:8pt;padding:10px 8px;border-top:1px solid #9ca3af;border-bottom:1px solid #9ca3af}
        .data-table td{border-bottom:1px solid #e5e7eb;padding:8px;vertical-align:middle}
        .text-right{text-align:right}
        .text-center{text-align:center}
        .font-bold{font-weight:700}
        .text-sm{font-size:8pt;color:#666}
        .total-section{width:100%;margin-top:10px}
        .total-table{width:45%;float:right;border-collapse:collapse}
        .total-table td{padding:8px;font-size:10pt}
        .grand-total-row{border-top:2px solid #333;background-color:#f3f4f6}
        .grand-total-label{font-size:11pt;font-weight:700}
        .grand-total-value{font-size:12pt;font-weight:700}
        .footer{position:fixed;bottom:0;left:0;right:0;font-size:8pt;color:#999;border-top:1px solid #eee;padding:5px 20px;text-align:center;background:#fff}
    </style>
</head>

<body>

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('assets/img/about-logo.png') }}" alt="Logo" style="width: 200px">
            </td>
            <td class="company-info">
                <h1 class="company-name">{{ $setting?->company_name ?? 'Bengawan Computer' }}</h1>
                <p class="company-detail">{{ $setting?->address ?? '' }}</p>
                @if (!empty($setting?->phone))
                    <p class="company-detail">Telp: {{ $setting?->phone }}</p>
                @endif
            </td>
        </tr>
    </table>

    {{-- Title --}}
    <div class="invoice-title">
        <h2>Nota Penjualan</h2>
        <div class="invoice-number">{{ $invoiceNumber }}</div>
    </div>

    {{-- Transaction Info --}}
    <table class="info-table">
        <tr>
            <td class="info-label">Tanggal</td>
            <td class="info-value">{{ $sale->transaction_date?->translatedFormat('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">Customer</td>
            <td class="info-value">{{ $sale->customer_info ?? 'Umum' }}</td>
        </tr>
        @if ($sale->sales_channel)
            <tr>
                <td class="info-label">Channel</td>
                <td class="info-value">{{ ucfirst($sale->sales_channel) }}</td>
            </tr>
        @endif
    </table>

    {{-- Product Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="45%">Produk</th>
                <th class="text-center" width="10%">Qty</th>
                <th class="text-right" width="20%">Harga Satuan</th>
                <th class="text-right" width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <span class="font-bold">{{ $productName }}</span>
                    @if ($productSku)
                        <br><span class="text-sm">SN: {{ $productSku }}</span>
                    @endif
                </td>
                <td class="text-center">{{ $sale->quantity ?? 0 }}</td>
                <td class="text-right">Rp {{ number_format((float) ($sale->negotiated_price ?? 0), 0, ',', '.') }}</td>
                <td class="text-right font-bold">Rp {{ number_format((float) ($sale->negotiated_price ?? 0) * (int) ($sale->quantity ?? 0), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Total --}}
    <div class="total-section">
        <table class="total-table">
            <tr>
                <td>Jumlah Item</td>
                <td class="text-right font-bold">{{ $sale->quantity ?? 0 }} unit</td>
            </tr>
            <tr class="grand-total-row">
                <td class="grand-total-label">Total</td>
                <td class="text-right grand-total-value">
                    Rp {{ number_format((float) ($sale->negotiated_price ?? 0) * (int) ($sale->quantity ?? 0), 0, ',', '.') }}
                </td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}
    </div>

</body>

</html>
