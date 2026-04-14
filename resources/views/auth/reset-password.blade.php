<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-600">Reset Password</p>
        <h2 class="mt-3 text-3xl font-semibold text-slate-900">Buat Password Baru</h2>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" required autofocus autocomplete="username">
            @error('email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="password" class="text-sm font-medium text-slate-700">Password</label>
            <input id="password" type="password" name="password" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" required autocomplete="new-password">
            @error('password')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="text-sm font-medium text-slate-700">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" required autocomplete="new-password">
            @error('password_confirmation')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            Reset Password
        </button>
    </form>
</x-guest-layout>
