<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()->latest()->get();

        return view('users.index', [
            'users' => $users,
            'summary' => [
                'total' => $users->count(),
                'admin' => $users->where('role', 'admin')->count(),
                'pimpinan' => $users->where('role', 'pimpinan')->count(),
                'staff' => $users->where('role', 'staff')->count(),
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->authorize('create', User::class);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        $this->auditLogService->record(
            $request->user(),
            'user.created',
            'Membuat user baru',
            $user,
            $user->only(['name', 'username', 'email', 'role'])
        );

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $this->authorize('update', $user);

        $user->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $this->auditLogService->record(
            $request->user(),
            'user.updated',
            'Memperbarui user',
            $user,
            $user->only(['name', 'username', 'email', 'role'])
        );

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        if (auth()->id() === $user->id) {
            return back()->withErrors([
                'user' => 'Kamu tidak bisa menghapus akun yang sedang dipakai.',
            ]);
        }

        $snapshot = $user->only(['name', 'username', 'email', 'role']);
        $user->delete();

        $this->auditLogService->record(
            request()->user(),
            'user.deleted',
            'Menghapus user',
            null,
            $snapshot
        );

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
