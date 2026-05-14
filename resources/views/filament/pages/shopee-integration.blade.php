<x-filament-panels::page>
    @once
        <style>
            .sic-wrap {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .sic-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 1rem;
            }

            .sic-card {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                padding: 1rem;
                box-shadow: 0 1px 2px rgba(15, 23, 42, .05);
            }

            .sic-title {
                margin: 0;
                font-size: .95rem;
                font-weight: 700;
                color: #111827;
            }

            .sic-desc {
                margin: .25rem 0 0;
                font-size: .82rem;
                color: #6b7280;
                line-height: 1.5;
            }

            .sic-status {
                display: inline-flex;
                align-items: center;
                gap: .45rem;
                border-radius: 999px;
                padding: .25rem .65rem;
                font-size: .72rem;
                font-weight: 700;
                border: 1px solid transparent;
                margin-top: .75rem;
            }

            .sic-dot {
                width: .5rem;
                height: .5rem;
                border-radius: 999px;
                display: inline-block;
            }

            .sic-success {
                color: #166534;
                background: #dcfce7;
                border-color: #bbf7d0;
            }

            .sic-success .sic-dot {
                background: #22c55e;
            }

            .sic-warning {
                color: #92400e;
                background: #fef3c7;
                border-color: #fde68a;
            }

            .sic-warning .sic-dot {
                background: #f59e0b;
            }

            .sic-danger {
                color: #991b1b;
                background: #fee2e2;
                border-color: #fecaca;
            }

            .sic-danger .sic-dot {
                background: #ef4444;
            }

            .sic-muted {
                color: #374151;
                background: #f3f4f6;
                border-color: #e5e7eb;
            }

            .sic-muted .sic-dot {
                background: #9ca3af;
            }

            .sic-list {
                display: flex;
                flex-direction: column;
                gap: .75rem;
                margin-top: 1rem;
            }

            .sic-row {
                display: flex;
                justify-content: space-between;
                gap: 1rem;
                padding-bottom: .75rem;
                border-bottom: 1px solid #f3f4f6;
            }

            .sic-row:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .sic-label {
                font-size: .78rem;
                color: #6b7280;
            }

            .sic-value {
                font-size: .78rem;
                font-weight: 600;
                color: #111827;
                text-align: right;
                word-break: break-all;
            }

            .sic-alert {
                border-radius: 14px;
                padding: 1rem;
                border: 1px solid #fde68a;
                background: #fffbeb;
                color: #92400e;
                font-size: .85rem;
                line-height: 1.55;
            }

            .sic-alert strong {
                display: block;
                margin-bottom: .25rem;
                color: #78350f;
            }

            :is(.dark .sic-card) {
                background: rgba(24, 24, 27, 1);
                border-color: rgba(255, 255, 255, .1);
                box-shadow: none;
            }

            :is(.dark .sic-title),
            :is(.dark .sic-value) {
                color: #fff;
            }

            :is(.dark .sic-desc),
            :is(.dark .sic-label) {
                color: #9ca3af;
            }

            :is(.dark .sic-row) {
                border-color: rgba(255, 255, 255, .08);
            }

            :is(.dark .sic-alert) {
                background: rgba(245, 158, 11, .12);
                border-color: rgba(245, 158, 11, .25);
                color: #fcd34d;
            }

            :is(.dark .sic-alert strong) {
                color: #fde68a;
            }

            @media (max-width: 1024px) {
                .sic-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endonce

    @php
        $status = $this->status;

        $connected = $status['connected'] ?? false;
        $tokenStatus = $status['token_status'] ?? 'not_connected';

        $statusClass = match ($tokenStatus) {
            'active' => 'sic-success',
            'expiring_soon' => 'sic-warning',
            'expired' => 'sic-danger',
            default => 'sic-muted',
        };

        $statusText = match ($tokenStatus) {
            'active' => 'Connected',
            'expiring_soon' => 'Token Hampir Expired',
            'expired' => 'Token Expired',
            default => 'Not Connected',
        };
    @endphp

    <div class="sic-wrap">
        @if (!$connected)
            <div class="sic-alert">
                <strong>Shopee belum terhubung.</strong>
                Klik tombol <b>Hubungkan Shopee</b> di kanan atas halaman ini, lalu login menggunakan akun seller
                sandbox/production yang sesuai.
            </div>
        @elseif ($tokenStatus === 'expired')
            <div class="sic-alert">
                <strong>Token Shopee sudah expired.</strong>
                Klik <b>Re-authorize Shopee</b> atau <b>Refresh Token</b> agar sinkronisasi produk dan stok bisa
                berjalan kembali.
            </div>
        @endif

        <div class="sic-grid">
            <div class="sic-card">
                <h2 class="sic-title">Status Koneksi</h2>
                <p class="sic-desc">
                    Menunjukkan apakah Bengawan sudah terhubung dengan toko Shopee.
                </p>

                <div class="sic-status {{ $statusClass }}">
                    <span class="sic-dot"></span>
                    {{ $statusText }}
                </div>

                @if ($this->lastTestMessage)
                    <p class="sic-desc" style="margin-top: .75rem;">
                        {{ $this->lastTestMessage }}
                    </p>
                @endif
            </div>

            <div class="sic-card">
                <h2 class="sic-title">Toko Shopee</h2>
                <p class="sic-desc">
                    Informasi toko yang sudah memberi izin akses ke aplikasi Bengawan.
                </p>

                <div class="sic-list">
                    <div class="sic-row">
                        <span class="sic-label">Shop Name</span>
                        <span class="sic-value">{{ $status['shop_name'] ?? '-' }}</span>
                    </div>

                    <div class="sic-row">
                        <span class="sic-label">Shop ID</span>
                        <span class="sic-value">{{ $status['shop_id'] ?? '-' }}</span>
                    </div>

                    <div class="sic-row">
                        <span class="sic-label">Token Expired</span>
                        <span class="sic-value">{{ $status['token_expires_at'] ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="sic-card">
                <h2 class="sic-title">Konfigurasi Sistem</h2>
                <p class="sic-desc">
                    Ringkasan konfigurasi integrasi yang terbaca dari environment Laravel.
                </p>

                <div class="sic-list">
                    <div class="sic-row">
                        <span class="sic-label">Partner ID</span>
                        <span class="sic-value">{{ $status['partner_id'] ?? '-' }}</span>
                    </div>

                    <div class="sic-row">
                        <span class="sic-label">Shopee Host</span>
                        <span class="sic-value">{{ $status['shopee_host'] ?? '-' }}</span>
                    </div>

                    <div class="sic-row">
                        <span class="sic-label">Redirect URL</span>
                        <span class="sic-value">{{ $status['redirect_url'] ?? '-' }}</span>
                    </div>

                    <div class="sic-row">
                        <span class="sic-label">Webhook Verify</span>
                        <span
                            class="sic-value">{{ $status['webhook_verify'] ?? false ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sic-card">
            <h2 class="sic-title">Panduan Penggunaan</h2>
            <p class="sic-desc">
                Gunakan tombol <b>Hubungkan Shopee</b> untuk authorization pertama.
                Gunakan <b>Re-authorize Shopee</b> jika token bermasalah atau toko ingin diganti.
                Gunakan <b>Test Koneksi</b> untuk memastikan API Shopee dapat diakses dari server.
            </p>
        </div>
    </div>
</x-filament-panels::page>
