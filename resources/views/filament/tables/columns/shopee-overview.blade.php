@once
    <style>
        .sov-card{width:min(230px, 100%);min-width:210px;max-width:230px;background:#fff;border-radius:12px;padding:10px 12px;font-size:12px;line-height:1.35;transition:background-color .18s ease,border-color .18s ease,box-shadow .18s ease,transform .18s ease}tr:hover .sov-card,.fi-ta-row:hover .sov-card,.fi-ta-cell:hover .sov-card,.sov-card:hover{background:#f9fafb;border-color:#dbe3ef;box-shadow:0 4px 10px rgb(15 23 42 / .08);transform:translateY(-1px)}.sov-header{display:flex;align-items:flex-start;justify-content:space-between;gap:8px}.sov-title-wrap{min-width:0;flex:1}.sov-title-row{display:flex;align-items:center;gap:7px;min-width:0}.sov-dot{width:8px;height:8px;border-radius:999px;flex:0 0 auto}.sov-dot-success{background:#22c55e}.sov-dot-danger{background:#ef4444}.sov-dot-warning{background:#f59e0b}.sov-dot-muted{background:#9ca3af}.sov-title{display:block;max-width:145px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;font-size:12px;font-weight:700;color:#111827}.sov-subtitle{margin-top:3px;max-width:170px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;font-size:11px;color:#6b7280}.sov-badge{display:inline-flex;align-items:center;justify-content:center;white-space:nowrap;border-radius:999px;border:1px solid #fff0;padding:2px 7px;font-size:10px;font-weight:700;line-height:16px;text-transform:uppercase;flex:0 0 auto}.sov-badge-success{background:#dcfce7;color:#166534;border-color:#bbf7d0}.sov-badge-danger{background:#fee2e2;color:#991b1b;border-color:#fecaca}.sov-badge-warning{background:#fef3c7;color:#92400e;border-color:#fde68a}.sov-badge-muted{background:#f3f4f6;color:#374151;border-color:#e5e7eb}.sov-status-grid{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:9px}.sov-status-box{border:1px solid #e5e7eb;border-radius:8px;padding:6px 7px;background:#f9fafb;min-width:0}.sov-status-label{display:block;font-size:10px;color:#6b7280;margin-bottom:3px}.sov-status-value{display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;font-size:11px;font-weight:700;color:#111827}.sov-footer{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:9px;padding-top:7px;border-top:1px solid #f3f4f6;font-size:11px;color:#6b7280}.sov-footer strong{color:#374151;font-weight:700}.sov-reason{margin-top:7px;padding:7px 8px;border-radius:8px;background:#fef2f2;color:#b91c1c;font-size:11px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}:is(.dark .sov-card){background:rgb(24 24 27);border-color:#fff0;box-shadow:none}:is(.dark tr:hover .sov-card),:is(.dark .fi-ta-row:hover .sov-card),:is(.dark .fi-ta-cell:hover .sov-card),:is(.dark .sov-card:hover){background:rgb(36 36 39)}:is(.dark .sov-title),:is(.dark .sov-status-value){color:#fff}:is(.dark .sov-subtitle),:is(.dark .sov-status-label),:is(.dark .sov-footer){color:#9ca3af}:is(.dark .sov-status-box){background:rgb(255 255 255 / .04);border-color:rgb(255 255 255 / .08)}:is(.dark .sov-footer){border-color:rgb(255 255 255 / .08)}:is(.dark .sov-footer strong){color:#e5e7eb}:is(.dark .sov-badge-success){background:rgb(34 197 94 / .12);color:#86efac;border-color:rgb(34 197 94 / .25)}:is(.dark .sov-badge-danger){background:rgb(239 68 68 / .12);color:#fca5a5;border-color:rgb(239 68 68 / .25)}:is(.dark .sov-badge-warning){background:rgb(245 158 11 / .12);color:#fcd34d;border-color:rgb(245 158 11 / .25)}:is(.dark .sov-badge-muted){background:rgb(255 255 255 / .08);color:#d1d5db;border-color:rgb(255 255 255 / .12)}:is(.dark .sov-reason){background:rgb(239 68 68 / .12);color:#fca5a5}@media (max-width:768px){.sov-card{width:100%;min-width:0;max-width:none;padding:9px 10px}.sov-header{align-items:flex-start}.sov-title{max-width:100%;font-size:11px}.sov-subtitle{max-width:100%;font-size:10px}.sov-status-grid{grid-template-columns:1fr;gap:5px}.sov-status-box{padding:5px 6px}.sov-footer{align-items:flex-start;flex-direction:column;gap:2px}.sov-badge{font-size:9px;padding:1px 6px}}@media (max-width:480px){.sov-card{border-radius:10px;padding:8px}.sov-status-grid{display:none}.sov-footer{margin-top:6px;padding-top:6px}}
    </style>
@endonce

@php
    $record = $getRecord();

    $hasActiveShopeeItem = filled($record->shopee_item_id);

    $hasShopeeProblem = $hasActiveShopeeItem && in_array($record->shopee_item_status, ['deleted', 'not_found'], true);

    $isPending =
        in_array($record->shopee_publish_status, ['pending'], true) ||
        in_array($record->shopee_sync_status, ['pending'], true);

    $isConnected = $hasActiveShopeeItem && !$hasShopeeProblem;

    $title = match (true) {
        $hasShopeeProblem => 'Shopee Bermasalah',
        $isConnected => 'Terhubung Shopee',
        $isPending => 'Sedang Diproses',
        default => 'Belum Publish',
    };

    $dotClass = match (true) {
        $hasShopeeProblem => 'sov-dot-danger',
        $isConnected => 'sov-dot-success',
        $isPending => 'sov-dot-warning',
        default => 'sov-dot-muted',
    };

    $itemStatus = match (true) {
        $hasShopeeProblem => $record->shopee_item_status,
        $isConnected => $record->shopee_item_status ?: 'normal',
        default => null,
    };

    $publishStatus = match (true) {
        $hasActiveShopeeItem => $record->shopee_publish_status ?: 'success',
        $isPending => 'pending',
        default => 'belum publish',
    };

    $syncStatus = $record->shopee_sync_status ?: 'belum sync';

    $lastSyncedAt = $record->shopee_last_synced_at ? $record->shopee_last_synced_at->format('d M H:i') : '-';

    $badgeClass = function (?string $state): string {
        return match ($state) {
            'success', 'normal', 'restored' => 'sov-badge-success',
            'failed', 'deleted' => 'sov-badge-danger',
            'pending', 'not_found' => 'sov-badge-warning',
            default => 'sov-badge-muted',
        };
    };

    $itemId = $record->shopee_item_id ?: '-';
@endphp

<div class="sov-card">
    <div class="sov-header">
        <div class="sov-title-wrap">
            <div class="sov-title-row">
                <span class="sov-dot {{ $dotClass }}"></span>
                <span class="sov-title">{{ $title }}</span>
            </div>

            <div class="sov-subtitle">
                Item ID: {{ $itemId }}
            </div>
        </div>

        @if ($itemStatus)
            <span class="sov-badge {{ $badgeClass($itemStatus) }}">
                {{ strtoupper($itemStatus) }}
            </span>
        @endif
    </div>

    <div class="sov-status-grid">
        <div class="sov-status-box">
            <span class="sov-status-label">Publish</span>
            <span class="sov-status-value">{{ $publishStatus }}</span>
        </div>

        <div class="sov-status-box">
            <span class="sov-status-label">Sync</span>
            <span class="sov-status-value">{{ $syncStatus }}</span>
        </div>
    </div>

    <div class="sov-footer">
        <span>Last sync</span>
        <strong>{{ $lastSyncedAt }}</strong>
    </div>

    @if ($hasShopeeProblem && $record->shopee_unlinked_reason)
        <div class="sov-reason">
            {{ $record->shopee_unlinked_reason }}
        </div>
    @endif
</div>
