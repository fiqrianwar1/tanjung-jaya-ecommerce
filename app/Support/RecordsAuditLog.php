<?php

namespace App\Support;

use App\Models\AuditLog;

trait RecordsAuditLog
{
    /**
     * Simpan jejak audit aktivitas admin.
     *
     * @param  array<string, mixed>|null  $oldData
     * @param  array<string, mixed>|null  $newData
     */
    protected function recordAudit(string $action, ?int $targetId = null, ?array $oldData = null, ?array $newData = null): void
    {
        if (! auth()->check()) {
            return;
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'target_id' => $targetId,
            'old_data' => $oldData,
            'new_data' => $newData,
        ]);
    }
}
