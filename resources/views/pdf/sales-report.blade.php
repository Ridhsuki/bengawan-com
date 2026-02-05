<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo-container {
            width: 20%;
            vertical-align: middle;
        }

        .company-info {
            width: 80%;
            text-align: right;
            vertical-align: middle;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .company-detail {
            margin: 2px 0;
            font-size: 9pt;
            color: #555;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-period {
            font-size: 10pt;
            color: #666;
            margin-top: 5px;
        }

        /* Tabel Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .data-table th {
            background-color: #f4f4f4;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Helper Classes */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-success {
            color: #166534;
        }

        /* Hijau tua profesional */

        /* Summary Box */
        .summary-wrapper {
            width: 100%;
            margin-top: 10px;
        }

        .summary-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 5px;
            border: none;
        }

        .grand-total {
            border-top: 2px solid #333 !important;
            font-size: 11pt;
            font-weight: bold;
            padding-top: 10px !important;
        }

        /* Tanda Tangan / Footer */
        .footer-signature {
            width: 100%;
            margin-top: 60px;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            float: right;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
            width: 100%;
            display: block;
        }

        /* Meta Info (Printed at) */
        .meta-info {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('assets/img/about-logo.png') }}" alt="Logo" style="width: 244px">
            </td>
            <td class="company-info">
                <h1 class="company-name">{{ $setting->company_name ?? 'Bengawan Komputer' }}</h1>
                <p class="company-detail">{{ $setting->address ?? '-' }}</p>
                <p class="company-detail">
                    Telp: {{ $setting->phone ?? '-' }} {{-- | Email: {{ $setting->email ?? '-' }} --}}
                </p>
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
                <th width="15%">Tanggal</th>
                <th width="35%">Produk</th>
                <th class="text-center" width="8%">Qty</th>
                <th class="text-right" width="17%">Harga Jual</th>
                <th class="text-right" width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $index => $sale)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $sale->transaction_date->format('d/m/Y') }}</td>
                    <td>
                        <span class="font-bold">{{ $sale->product->name }}</span>
                        @if ($sale->product->serial_number)
                            <br><small style="color: #666; font-size: 8pt;">SN:
                                {{ $sale->product->serial_number }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $sale->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($sale->selling_price, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp
                        {{ number_format($sale->selling_price * $sale->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-wrapper">
        <table class="summary-table">
            <tr>
                <td>Total Omset</td>
                <td class="text-right">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="text-success">Total Laba (Profit)</td>
                <td class="text-right text-success">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="grand-total text-right">
                    Rp {{ number_format($totalOmset, 0, ',', '.') }}
                </td>
            </tr>
        </table>
        <div style="clear: both;"></div>
    </div>

    {{-- <div class="footer-signature">
        <div class="signature-box">
            <p>Disetujui Oleh,</p>
            <span class="signature-line"></span>
            <p class="font-bold">Admin / Manager</p>
        </div>
    </div> --}}

    <div class="meta-info">
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}
    </div>

    <script type="text/php">
    if (isset($pdf)) {
        $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
        $font = $fontMetrics->get_font("helvetica", "normal");
        $size = 8;
        $color = array(0.6, 0.6, 0.6);

        $pdf->page_text(450, 810, $text, $font, $size, $color);
    }
    </script>
</body>

</html>
