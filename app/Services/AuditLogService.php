<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AuditLogService
{
    public function record(?Model $user, string $action, string $description, ?Model $auditable = null, array $properties = []): AuditLog
    {
        $payload = [
            'user_id' => $user?->getKey(),
            'action' => $action,
            'description' => $description,
            'auditable_type' => $auditable?->getMorphClass(),
            'auditable_id' => $auditable?->getKey(),
            'properties' => $properties,
        ];

        try {
            return AuditLog::create($payload);
        } catch (QueryException $exception) {
            if (! $this->isMissingAuditLogTable($exception)) {
                throw $exception;
            }

            Log::warning('Audit log skipped because the audit_logs table is missing.', [
                'action' => $action,
                'description' => $description,
            ]);

            return new AuditLog($payload);
        }
    }

    private function isMissingAuditLogTable(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'audit_logs')
            && (
                ($exception->errorInfo[1] ?? null) === 1146
                || str_contains($message, 'base table or view not found')
            );
    }
}
