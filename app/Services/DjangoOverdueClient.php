<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DjangoOverdueClient
{
    public function syncOverdueTasks(): bool
    {
        try {
            $response = Http::timeout(10)->post($this->baseUrl().'/api/overdue/sync/');

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('Django overdue sync failed: '.$e->getMessage());

            return false;
        }
    }

    public function validateStatusChange(int $taskId, string $newStatus, string $userRole): array
    {
        try {
            $response = Http::timeout(10)->post($this->baseUrl().'/api/overdue/validate-status/', [
                'task_id' => $taskId,
                'new_status' => $newStatus,
                'user_role' => $userRole,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'allowed' => false,
                'message' => $response->json('message') ?? 'Status change rejected by overdue service.',
            ];
        } catch (\Throwable $e) {
            Log::warning('Django validate-status failed: '.$e->getMessage());

            return [
                'allowed' => false,
                'message' => 'Overdue validation service unavailable.',
            ];
        }
    }

    private function baseUrl(): string
    {
        return rtrim(config('services.django.url', 'http://localhost:8001'), '/');
    }
}
