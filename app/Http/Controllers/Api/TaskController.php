<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\ApiResponse;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService)
    {
    }

    public function index(Request $request)
    {
        $tasks = $this->taskService->listForUser(
            $request->user(),
            $request->integer('project_id') ?: null
        );

        return ApiResponse::success(['tasks' => $tasks]);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = Task::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? Task::STATUS_TODO,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
        ]);

        $task->load(['project', 'assignee']);

        return ApiResponse::success(['task' => $task], 'Task created', 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        if ($request->has('status')) {
            $error = $this->taskService->validateStatusChange(
                $task,
                $request->status,
                $request->user()
            );

            if ($error) {
                return ApiResponse::error($error, 422);
            }
        }

        $task->update($request->only([
            'title', 'description', 'status', 'priority', 'due_date', 'assigned_to',
        ]));

        $task->load(['project', 'assignee']);

        return ApiResponse::success(['task' => $task], 'Task updated');
    }
}
