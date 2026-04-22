<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Task::query()->with('project');

        if (! $user->hasRole('admin')) {
            $query->whereHas('project', fn ($projectQuery) => $projectQuery->where('owner_id', $user->id));
        }

        return $this->success('Tasks fetched.', $query->latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['todo', 'in_progress', 'done'])],
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorizeProjectAccess($request->user(), $project);

        $task = Task::create($validated);

        return $this->success('Task created.', $task, 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorizeProjectAccess($request->user(), $task->project);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', Rule::in(['todo', 'in_progress', 'done'])],
        ]);

        // Prevent reassignment to unrelated projects through mass assignment.
        unset($validated['project_id']);

        $task->update($validated);

        return $this->success('Task updated.', $task);
    }

    private function authorizeProjectAccess($user, Project $project): void
    {
        if (! $user->hasRole('admin') && $project->owner_id !== $user->id) {
            abort(403);
        }
    }
}
