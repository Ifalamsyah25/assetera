<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect([
            'admin' => 'Full access',
            'staff' => 'Operational access',
            'viewer' => 'Read only access',
        ])->map(fn (string $description, string $name) => Role::query()->firstOrCreate(
            ['name' => $name],
            ['description' => $description]
        ));

        $permissionCodes = [
            'project.read',
            'project.create',
            'project.update',
            'task.read',
            'task.create',
            'task.update',
        ];

        $permissions = collect($permissionCodes)->mapWithKeys(function (string $code) {
            $permission = Permission::query()->firstOrCreate(
                ['code' => $code],
                ['description' => $code]
            );

            return [$code => $permission];
        });

        $roles['admin']->permissions()->sync($permissions->pluck('id')->all());
        $roles['staff']->permissions()->sync($permissions->only([
            'project.read',
            'project.create',
            'project.update',
            'task.read',
            'task.create',
            'task.update',
        ])->pluck('id')->all());
        $roles['viewer']->permissions()->sync($permissions->only([
            'project.read',
            'task.read',
        ])->pluck('id')->all());

        $firstUser = User::query()->first();
        if ($firstUser) {
            $firstUser->roles()->syncWithoutDetaching([$roles['admin']->id]);
        }
    }
}
