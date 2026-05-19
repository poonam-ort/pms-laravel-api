<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(private DjangoOverdueClient $djangoClient)
    {
    }

    public function syncOverdue(): void
    {
        $this->djangoClient->syncOverdueTasks();
    }

    public function listForUser(User $user, ?int $projectId = null): Collection
    {
        $this->syncOverdue();

        $query = Task::with(['project', 'assignee'])
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId));

        if (! $user->isAdmin()) {
            $query->where('assigned_to', $user->id);
        }

        return $query->orderBy('due_date')->get();
    }

    public function validateStatusChange(Task $task, string $newStatus, User $user): ?string
    {
        $this->syncOverdue();
        $task->refresh();

        $result = $this->djangoClient->validateStatusChange(
            $task->id,
            $newStatus,
            $user->role
        );

        if (! ($result['allowed'] ?? false)) {
            return $result['message'] ?? 'Invalid status transition.';
        }

        return null;
    }
}
