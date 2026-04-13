<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-600">Password Reset</p>
        <h2 class="mt-3 text-3xl font-semibold text-slate-900">Lupa Password</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Masukkan email akun, lalu sistem akan mengirimkan link untuk reset password.
        </p>
    </div>

    <x-auth-session-status class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" required autofocus>
            @error('email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            Email Password Reset Link
        </button>
    </form>
</x-guest-layout>
