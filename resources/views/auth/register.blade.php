<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-600">Register</p>
        <h2 class="mt-3 text-3xl font-semibold text-slate-900">Buat Akun Baru</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Pendaftaran ini membuat akun baru dengan role default <strong>staff</strong>.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="text-sm font-medium text-slate-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" required autofocus autocomplete="name">
            @error('name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="username" class="text-sm font-medium text-slate-700">Username</label>
            <input id="username" type="text" name="username" value="{{ old('username') }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" required autocomplete="username">
            @error('username')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label for="email" class="text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 shadow-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" required autocomplete="email">
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

        <div class="flex items-center justify-between gap-4 pt-2">
            <a class="text-sm text-blue-600 hover:text-blue-700" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>
