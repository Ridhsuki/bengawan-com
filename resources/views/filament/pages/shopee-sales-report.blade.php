<x-filament-panels::page>
    @once
        <style>
            .ssr-wrap{display:flex;flex-direction:column;gap:1rem}.ssr-toolbar{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap}.ssr-title{font-size:1rem;font-weight:700;color:#111827;margin:0}.ssr-desc{font-size:.85rem;color:#6b7280;margin-top:.25rem;line-height:1.55}.ssr-actions{display:flex;flex-wrap:wrap;gap:.5rem;align-items:center}.ssr-btn{border:1px solid #d1d5db;background:#fff;color:#374151;border-radius:.6rem;padding:.45rem .75rem;font-size:.8rem;font-weight:600;cursor:pointer;transition:background-color .15s ease,border-color .15s ease,color .15s ease}.ssr-btn:hover{background:#f9fafb}.ssr-btn-active{background:#2563eb;border-color:#2563eb;color:#fff}.ssr-select{border:1px solid #d1d5db;background:#fff;color:#374151;border-radius:.6rem;padding:.45rem .75rem;font-size:.8rem;font-weight:600;min-width:170px}.ssr-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1rem}.ssr-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:1rem;box-shadow:0 1px 2px rgb(15 23 42 / .05)}.ssr-card-label{color:#6b7280;font-size:.8rem}.ssr-card-value{margin-top:.35rem;color:#111827;font-size:1.35rem;font-weight:800}.ssr-table-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;box-shadow:0 1px 2px rgb(15 23 42 / .05)}.ssr-table-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem;border-bottom:1px solid #e5e7eb}.ssr-table-title{font-size:.95rem;font-weight:700;color:#111827}.ssr-table-subtitle{margin-top:.2rem;font-size:.78rem;color:#6b7280}.ssr-table-wrap{overflow-x:auto}.ssr-table{width:100%;border-collapse:collapse;font-size:.82rem}.ssr-table th{background:#f9fafb;color:#374151;font-weight:700;text-align:left;padding:.75rem 1rem;border-bottom:1px solid #e5e7eb;white-space:nowrap}.ssr-table td{padding:.75rem 1rem;border-bottom:1px solid #f3f4f6;color:#374151;vertical-align:top;white-space:nowrap}.ssr-product{font-weight:700;color:#111827;white-space:normal;min-width:220px}.ssr-muted{color:#6b7280;font-size:.75rem;margin-top:.2rem;line-height:1.45}.ssr-badge{display:inline-flex;border-radius:999px;padding:.15rem .55rem;font-size:.72rem;font-weight:700;background:#f3f4f6;color:#374151;white-space:nowrap}.ssr-badge-success{background:#dcfce7;color:#166534}.ssr-badge-warning{background:#fef3c7;color:#92400e}.ssr-badge-danger{background:#fee2e2;color:#991b1b}.ssr-alert{padding:.85rem 1rem;border-radius:14px;border:1px solid #bfdbfe;background:#eff6ff;color:#1e40af;font-size:.85rem;line-height:1.5}.ssr-empty{padding:1rem;color:#6b7280;font-size:.85rem;text-align:center}:is(.dark .ssr-title),:is(.dark .ssr-card-value),:is(.dark .ssr-table-title),:is(.dark .ssr-product){color:#fff}:is(.dark .ssr-desc),:is(.dark .ssr-card-label),:is(.dark .ssr-muted),:is(.dark .ssr-table-subtitle),:is(.dark .ssr-empty){color:#9ca3af}:is(.dark .ssr-card),:is(.dark .ssr-table-card){background:rgb(24 24 27);border-color:rgb(255 255 255 / .1);box-shadow:none}:is(.dark .ssr-table-head),:is(.dark .ssr-table th),:is(.dark .ssr-table td){border-color:rgb(255 255 255 / .08)}:is(.dark .ssr-table th){background:rgb(255 255 255 / .04);color:#d1d5db}:is(.dark .ssr-table td){color:#d1d5db}:is(.dark .ssr-btn),:is(.dark .ssr-select){background:rgb(24 24 27);color:#d1d5db;border-color:rgb(255 255 255 / .12)}:is(.dark .ssr-btn-active){background:#2563eb;color:#fff;border-color:#2563eb}:is(.dark .ssr-alert){background:rgb(37 99 235 / .12);border-color:rgb(37 99 235 / .25);color:#93c5fd}.ssr-alert-danger{border-color:#fecaca;background:#fef2f2;color:#991b1b}:is(.dark .ssr-alert-danger){background:rgb(153 27 27 / .15);border-color:rgb(153 27 27 / .3);color:#fca5a5}@media (max-width:1024px){.ssr-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (max-width:640px){.ssr-grid{grid-template-columns:1fr}.ssr-toolbar,.ssr-table-head{flex-direction:column;align-items:stretch}.ssr-actions{width:100%}.ssr-btn,.ssr-select{flex:1}}
        </style>
    @endonce

    @php
        $summary = $this->summary;
        $statusSummary = $this->statusSummary;
        $latestShopeeSales = $this->latestShopeeSales;
        $availableStatuses = $this->availableStatuses;

        $statusClass = function (?string $status) {
            return match ($status) {
                'COMPLETED', 'SHIPPED', 'PROCESSED', 'READY_TO_SHIP', 'TO_CONFIRM_RECEIVE' => 'ssr-badge-success',
                'CANCELLED', 'IN_CANCEL' => 'ssr-badge-danger',
                default => 'ssr-badge-warning',
            };
        };
    @endphp

    <div class="ssr-wrap">
        @if (!$this->isShopeeConnected)
            <div class="ssr-alert ssr-alert-danger">
                <strong>Shopee belum terhubung.</strong><br>
                Hubungkan toko Shopee terlebih dahulu melalui menu
                <b>Marketplace → Shopee Integration</b>.
                Setelah toko terhubung, gunakan tombol <b>Pull Order Shopee</b> atau
                <b>Pull & Sync Sekarang</b> untuk mengambil order.
            </div>
        @endif
        <div class="ssr-toolbar">
            <div>
                <h2 class="ssr-title">Laporan Penjualan Shopee</h2>
                <p class="ssr-desc">
                    Laporan ini hanya menampilkan order yang berasal dari checkout Shopee.
                    Penjualan internal tetap berada pada laporan internal.
                </p>
            </div>

            <div class="ssr-actions">
                <button type="button" wire:click="setPeriod('7')"
                    class="ssr-btn {{ $period === '7' ? 'ssr-btn-active' : '' }}">
                    7 Hari
                </button>

                <button type="button" wire:click="setPeriod('30')"
                    class="ssr-btn {{ $period === '30' ? 'ssr-btn-active' : '' }}">
                    30 Hari
                </button>

                <button type="button" wire:click="setPeriod('90')"
                    class="ssr-btn {{ $period === '90' ? 'ssr-btn-active' : '' }}">
                    90 Hari
                </button>

                <select wire:model.live="status" class="ssr-select">
                    <option value="all">Semua Status</option>
                    @foreach ($availableStatuses as $availableStatus)
                        <option value="{{ $availableStatus }}">{{ $availableStatus }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if ($lastPullMessage)
            <div class="ssr-alert">
                Pull order terakhir sudah dijalankan. Jika data belum muncul, pastikan queue worker berjalan.
            </div>
        @endif

        <div class="ssr-grid">
            <div class="ssr-card">
                <div class="ssr-card-label">Order Shopee</div>
                <div class="ssr-card-value">{{ number_format($summary['total_orders']) }}</div>
            </div>

            <div class="ssr-card">
                <div class="ssr-card-label">Item Terjual</div>
                <div class="ssr-card-value">{{ number_format($summary['total_items']) }}</div>
            </div>

            <div class="ssr-card">
                <div class="ssr-card-label">Omzet Shopee</div>
                <div class="ssr-card-value">{{ $this->formatRupiah($summary['total_revenue']) }}</div>
            </div>

            <div class="ssr-card">
                <div class="ssr-card-label">Estimasi Profit</div>
                <div class="ssr-card-value">{{ $this->formatRupiah($summary['total_profit']) }}</div>
            </div>
        </div>

        <div class="ssr-table-card">
            <div class="ssr-table-head">
                <div>
                    <div class="ssr-table-title">Ringkasan Status Order Shopee</div>
                    <div class="ssr-table-subtitle">Dikelompokkan berdasarkan status order dari Shopee.</div>
                </div>
            </div>

            <div class="ssr-table-wrap">
                <table class="ssr-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Item</th>
                            <th>Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($statusSummary as $row)
                            <tr>
                                <td>
                                    <span class="ssr-badge {{ $statusClass($row->external_status) }}">
                                        {{ $row->external_status ?: '-' }}
                                    </span>
                                </td>
                                <td>{{ number_format((int) $row->total_orders) }}</td>
                                <td>{{ number_format((int) $row->total_items) }}</td>
                                <td>{{ $this->formatRupiah((float) $row->total_revenue) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="ssr-empty">Belum ada order Shopee pada periode ini.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ssr-table-card">
            <div class="ssr-table-head">
                <div>
                    <div class="ssr-table-title">Order Shopee Terbaru</div>
                    <div class="ssr-table-subtitle">Menampilkan item order Shopee yang sudah masuk ke laporan internal.
                    </div>
                </div>
            </div>

            <div class="ssr-table-wrap">
                <table class="ssr-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Order SN</th>
                            <th>Status</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestShopeeSales as $sale)
                            <tr>
                                <td>{{ optional($sale->transaction_date)->format('d M Y') }}</td>
                                <td>
                                    <div class="ssr-product">
                                        {{ $sale->product_name_snapshot ?: $sale->product?->name ?? 'Produk Shopee tidak termapping' }}
                                    </div>
                                    @if (blank($sale->product_id))
                                        <div class="ssr-muted" style="color:#92400e;">
                                            Belum termapping ke produk internal
                                        </div>
                                    @endif
                                    <div class="ssr-muted">
                                        SKU: {{ $sale->product_sku_snapshot ?: '-' }}
                                        · Item: {{ $sale->external_item_id ?: '-' }}
                                        · Model: {{ $sale->external_model_id ?? 0 }}
                                    </div>
                                </td>
                                <td>{{ $sale->external_order_sn ?: '-' }}</td>
                                <td>
                                    <span class="ssr-badge {{ $statusClass($sale->external_status) }}">
                                        {{ $sale->external_status ?: '-' }}
                                    </span>
                                </td>
                                <td>{{ number_format((int) $sale->quantity) }}</td>
                                <td>{{ $this->formatRupiah($sale->negotiated_price) }}</td>
                                <td>{{ $this->formatRupiah($sale->total_profit) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="ssr-empty">Belum ada order Shopee pada periode ini.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
