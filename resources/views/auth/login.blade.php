<x-guest-layout title="Login">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-900">Masuk</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Login untuk mengakses dashboard pengelolaan aset.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="text-sm font-medium text-slate-700">Email</label>
            <input
                id="email"
                class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="admin@dapurmbg.test"
            />
            @error('email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-slate-600 transition hover:text-slate-900" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <input
                id="password"
                class="mt-2 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan password"
            />

            @error('password')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900" name="remember">
                <span>Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                Masuk
            </button>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-500">
            Akun demo:
            <span class="font-medium text-slate-700">admin@dapurmbg.test</span>
            <span class="mx-1">|</span>
            password:
            <span class="font-medium text-slate-700">password</span>
        </div>
    </form>
</x-guest-layout>
