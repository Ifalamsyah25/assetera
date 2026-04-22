<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Project::query();

        if (! $user->hasRole('admin')) {
            $query->where('owner_id', $user->id);
        }

        $projects = $query->latest()->get();

        return $this->success('Projects fetched.', $projects);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::create([
            ...$validated,
            'owner_id' => $request->user()->id,
        ]);

        return $this->success('Project created.', $project, 201);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $user = $request->user();

        if (! $user->hasRole('admin') && $project->owner_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        unset($validated['owner_id']);

        $project->update($validated);

        return $this->success('Project updated.', $project);
    }
}
